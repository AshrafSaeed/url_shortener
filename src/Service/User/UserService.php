<?php 

namespace App\Service\User;

use Exception;
use App\Entity\User;
use App\Entity\ApiToken;
use App\Service\BaseService;
use App\Validations\UserValidation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Transformers\UserTransformer as Transform;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

/**
 * Class ShortURLService
 * @package App\Service\Url
 *
*/

class UserService extends BaseService {

    /**
     * save EntityManagerInterface
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;        
    }

    /**
     * Login User
     *
     * @param Request $request 
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */

    public function login(Request $request)
    {
        try {

            $credentials = $this->em->getRepository(User::class)->getCredential($request);

            $validator = Validation::createValidator();
            $constraint = UserValidation::loginRule();
            $violations = $validator->validate($credentials, $constraint);
            
            if ($violations->count() > 0) {
                return $this->respondError(UserValidation::loginErrors($violations), 422);
            }

            if (!$this->em->getRepository(User::class)->attempt((array) $credentials)) {
                throw new CustomUserMessageAuthenticationException('The provided credentials do not match our records');
            }

            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
            
            $userToken = new ApiToken($user);
            $this->em->persist($userToken);
            $this->em->flush();

            return $this->respondCreated(Transform::user($user, $userToken->getToken()), 'User successfully login'); 

        } catch (Exception $ex) {
            return $this->respondError($ex->getMessage(), $ex->getCode());
        }
    }

    /**
     * Register new User
     *
     * @param Request $request 
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */

    public function register(Request $request)
    {
        try {

            $data = $this->em->getRepository(User::class)->getRegisterRequest($request);

            $validator = Validation::createValidator();
            $constraint = UserValidation::registerationRule();
            $violations = $validator->validate($data, $constraint);

            if ($violations->count() > 0) {
                return $this->respondError(UserValidation::loginErrors($violations), 422);
            }

            $user = new User();
            $user->setName($data['name'])
                ->setEmail($data['email'])
                ->setPassword(md5($data['password']))
                ->setCreatedAt(new \DateTime('now'))
                ->setUpdatedAt(new \DateTime('now'));

            $this->em->persist($user);
            $this->em->flush();

            $userToken = new ApiToken($user);
            $this->em->persist($userToken);
            $this->em->flush();

            return $this->respondCreated(Transform::user($user, $userToken->getToken()), 'User successfully register'); 

        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $ex) {
            return $this->respondError("User with given email already exists", 422);
        } catch(\Exception $ex) {
            return $this->respondError('message: '.$ex->getMessage(), $ex->getCode());
        } 
    }

    /**
     * Logout User
     *
     * @param Request $request 
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    
    public function logout(Request $request){

        $token = substr($request->headers->get('Authorization'), 7);
        $user = $this->em->getRepository(ApiToken::class)->findOneBy(['token' => $token]);

        if($user) {
            $this->em->remove($user);
            $this->em->flush();
            return $this->respondCreated([], 'user successfully logout');  
        } else {
            return $this->respondUnauthorized('you are not authorised');
        }
    }
}