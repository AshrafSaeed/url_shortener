<?php 

namespace App\Controller\Api;

use App\Service\User\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserController
 * @package App\Controller\Api
 *
 * @Route("/api")
*/
class UserController extends AbstractController {

	/**
     * @var $user
    */
    private $user;

    /**
     * UserController constructor.
     * @param UserService $userService
    */
    public function __construct(UserService $userService)
    {
        $this->user = $userService;
    }

    /**
     * @Route("/login", name="user-login")
     * @Method({"POST"})
     * 
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function authenticate(Request $request)
    {

        return $this->user->login($request);
    }

    /**
     * @Route("/register", name="user-register")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function register(Request $request)
    {
        return $this->user->register($request);
    }

    /**
     * @Route("/logout", name="user-logout")
     * @Method({"GET"})
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function logout(Request $request)
    {
        return $this->user->logout($request);
    }

}