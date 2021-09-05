<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    
    public function getCredential($request) {

        $request = $this->makeRequest($request);
        
        return [
            'email' => $request->request->get('email'), 
            'password' => md5($request->request->get('password'))
        ];
    }


    public function getRegisterRequest($request) {

        $request = $this->makeRequest($request);
        
        return [
            'name' => $request->request->get('name'),
            'email' => $request->request->get('email'), 
            'password' => $request->request->get('password')
        ];
    }


    private function makeRequest($request)
    {
        if ('json' === $request->getContentType() && $request->getContent()) {
           $data = json_decode($request->getContent(), true); 
           $request->request->replace($data);
        }

        return $request;
    }


    public function attempt($credential)
    {
        $user = $this->findBy($credential);

        return empty($user)? false : true ;
    }
}
