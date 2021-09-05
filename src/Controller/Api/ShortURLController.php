<?php 

namespace App\Controller\Api;

use App\Service\Url\ShortURLService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ShortURLController
 * @package App\Controller\Api
 *
 * @Route("/api")
*/
class ShortURLController extends AbstractController {

	/**
     * @var $url
    */
    private $url;

    /**
     * ShortURLController constructor.
     * @param ShortURLService $urlService
    */
    public function __construct(ShortURLService $urlService)
    {
        $this->url = $urlService;
    }

    /**
     * @Route("/url/list", name="url-list")
     * @Method({"GET"})
     * @param UserInterface $user
     * 
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function list(UserInterface $user)
    {
        return $this->url->list($user);
    }

    /**
     * @Route("/url/create", name="url-create")
     * @Method({"POST"})
     *
     * @param Request $request 
     * @param UserInterface $user
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request, UserInterface $user)
    {
        return $this->url->add($request, $user);
    }

    /**
     * @Route("/url/show/{id}", name="url-read")
     * @Method({"GET"})
     * @param int $id 
     * @param UserInterface $user
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function read($id, UserInterface $user)
    {
        return $this->url->show($id, $user);
    }

    /**
     * @Route("/url/update/{id}", name="url-update")
     * @Method({"PUT"})
     *
     * @param Request $request 
     * @param int $id 
     * @param UserInterface $user
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(Request $request, $id, UserInterface $user)
    {
        return $this->url->update($request, $id, $user);
    }

    /**
     * @Route("/url/delete/{id}", name="url-delete")
     * @Method({"DELETE"})
     *
     * @param int $id
     * @param UserInterface $user
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function destroy($id, UserInterface $user)
    {
        return $this->url->delete($id, $user);
    }


     /**
     * @Route("/all/url/list", name="all-url-list")
     * @Method({"GET"})
     * @param UserInterface $user
     * 
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function alllist()
    {
        return $this->url->alllist();
    }

}