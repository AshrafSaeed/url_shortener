<?php 

namespace App\Service\Url;

use App\Entity\User;
use App\Entity\ShortUrl;
use App\Service\BaseService;
use App\Validations\UrlValidation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Transformers\UrlTransformer as Transform;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ShortURLService
 * @package App\Service\Url
 *
*/

class ShortURLService extends BaseService {

    /**
     *  EntityManagerInterface
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * get URL list by user
     *
     * @param UserInterface $user
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */

    public function list(UserInterface $user)
    {
        try {

            $arrUrlList = $this->em->getRepository(ShortUrl::class)->findBy([
                'user' => $user->getId()
            ]);
            return $this->respondCreated(Transform::list($arrUrlList));   

        } catch (Exception $e) {
            return $this->respondError('message: '.$e->getMessage(), $e->getCode());
        }
    }

    /**
     * Add new URL by logged in user
     *
     * @param Request $request 
     * @param UserInterface $user
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */

    public function add(Request $request, UserInterface $user)
    {
        try {

            $data = $this->em->getRepository(ShortUrl::class)->getRequest($request);

            $validator = Validation::createValidator();
            $constraint = UrlValidation::urlRule();
            $violations = $validator->validate($data, $constraint);
            
            if ($violations->count() > 0) {
                return $this->respondError(UrlValidation::urlErrors($violations), 422);
            }

            //create randume string 
            $string = $this->genrateUrlToken(); 

            $shortURL = new ShortUrl();
            $shortURL->setFullUrl($data['url'])
                ->setUrlToken($string)
                ->setUser($user)
                ->setCreatedAt(new \DateTime('now'))
                ->setUpdatedAt(new \DateTime('now'));

            $this->em->persist($shortURL);
            $this->em->flush();

            return $this->respondCreated(Transform::url($shortURL), 'url successfully create');  
        } catch(\Exception $ex) {

            dd($ex);

            return $this->respondError('message: '.$ex->getMessage(), $ex->getCode());
        } 
    }

    /**
     * Show one URL by authorised user
     *
     * @param int $id 
     * @param UserInterface $user
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */

    public function show($id, UserInterface $user)
    {
        try {
            $url = $this->em->getRepository(ShortUrl::class)->findOneBy([
                'id' => $id, 'user' => $user->getId()
            ]);

            if($url) {
                return $this->respondCreated(Transform::url($url));   
            } else {
                return $this->respondNotFound('URL not found');
            }
        } catch (Exception $e) {
            return $this->respondError('message: '.$e->getMessage(), $e->getCode());
        }
    }

    /**
     * update URL list by authorised user
     *
     * @param Request $request 
     * @param int $id 
     * @param UserInterface $user
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(Request $request, $id, UserInterface $user)
    {
        try {

            $url = $this->em->getRepository(ShortUrl::class)->findOneBy([
                'id' => $id, 'user' => $user->getId()
            ]);

            if(!$url) {
                return $this->respondNotFound('URL not found');
            }

            $data = $this->em->getRepository(ShortUrl::class)->getRequest($request);

            $url->setFullUrl($data['url'])->setUpdatedAt(new \DateTime('now'));
            $this->em->persist($url);
            $this->em->flush();

            return $this->respondCreated(Transform::url($url), 'url successfully update'); 

        } catch (Exception $e) {
            return $this->respondError('message: '.$e->getMessage(), $e->getCode());
        }
    }

    /**
     * delete URL list by authorised user
     *
     * @param int $id 
     * @param UserInterface $user
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */

    public function delete($id, UserInterface $user)
    {
        try {

            $url = $this->em->getRepository(ShortUrl::class)->findOneBy([
                'id' => $id, 'user' => $user->getId()
            ]);

            if($url) {
                $this->em->remove($url);
                $this->em->flush();
                return $this->respondCreated([], 'url successfully delete');  
            } else {
                return $this->respondNotFound('URL not found');
            }
        } catch (Exception $e) {
            return $this->respondError('message: '.$e->getMessage(), $e->getCode());
        }
    }


    /**
     * genrate random URL token 
     *
     * @return string
     */

    private function genrateUrlToken()
    {
        do {
            $random = bin2hex(random_bytes(10));
            if (strlen($random) > 7) {
                $url = substr($random, 0, 7);
            }
            $respository = $this->em->getRepository(ShortUrl::class);
            $urlInDatabase = $respository->findOneBy([
                'url_token' => $url,
            ]);

        } while ($urlInDatabase);

        return $url;
    }

    /**
     * Get All URL list 
     * 
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function alllist()
    {
        try {

            $arrUrlList = $this->em->getRepository(ShortUrl::class)->findAll();
            return $this->respondCreated(Transform::list($arrUrlList));   

        } catch (Exception $e) {
            return $this->respondError('message: '.$e->getMessage(), $e->getCode());
        }
    }
}