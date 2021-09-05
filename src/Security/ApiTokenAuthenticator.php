<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{

    private $apiTokenRepo;

    function __construct (ApiTokenRepository $apiTokenRepo){

        $this->apiTokenRepo = $apiTokenRepo;
    }

    public function supports(Request $request)
    {

        return $request->headers->has('Authorization')
                && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    public function getCredentials(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');

        return substr($authorizationHeader, 7); 
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {

        $token = $this->apiTokenRepo->findOneBy([
            'token' => $credentials
        ]);

        if(!$token){
            throw new CustomUserMessageAuthenticationException('The provided token do not match');
        }

        if($token->isExpired()){
            throw new CustomUserMessageAuthenticationException('The provided token is expired');
        }

        return $token->getUser();
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return  new JsonResponse(["Response" => [
                          "success" => false,
                          "data" => null,
                          "message" => $exception->getMessage()
                        ]
                    ], JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // todo
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {

        return  new JsonResponse(["Response" => [
                          "success" => false,
                          "data" => null,
                          "message" => $authException->getMessage()
                        ]
                    ], JsonResponse::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
