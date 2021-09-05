<?php

namespace App\Controller;

use App\Entity\ShortUrl;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class UrlController
 * @package App\Controller
 *
 * @Route("/")
 */

final class UrlController extends AbstractController 
{
    
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
     * @Route("{token}", name="patients_index")
     */
    public function __invoke($token)
    {
        try {

            if ($objURL = $this->em->getRepository(ShortUrl::class)->findByToken($token)){
                return $this->redirect($objURL->getFullUrl());
            }

            return new JsonResponse(['message' => 'Invalid Short URL token']);

        } catch (\Exception $e) {
            return new Response($e->getMessage(), $e->getCode());
        }
    }

}