<?php

namespace App\Repository;

use App\Entity\ShortUrl;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ShortUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShortUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShortUrl[]    findAll()
 * @method ShortUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class ShortUrlRepository extends ServiceEntityRepository
{
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShortUrl::class);
    }

    
    public function findByToken($token)
    {
        $url = $this->findOneBy(['url_token' => $token]);
        return $url ? $url : false;
    }
   
    public function getRequest($request) {

        $request = $this->makeRequest($request);
        
        return [
            'url' => $request->request->get('url'),
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

}
