<?php

namespace App\Service;
    
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class BaseService
 * @package App\Service
 */
class BaseService
{

    /**
     * @var integer by default HTTP status code - 200 (OK) 
     */
    protected $server = '';
    protected $statusCode = 200;

    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }


     /**
     * Returns a 201 Created
     *
     * @param array $data string $message
     *
     * @return JsonResponse
     */
    public function respondCreated($data = [], $message = null)
    {
        $response = ["Response" => [
                          "success" => true,
                          "data" => $data,
                          "message" => $message
                        ]
                    ];
        return $this->setStatusCode(201)->respond($response);
    }

    /**
     * Sets an error message and returns a JSON response
     *
     * @param string $errors
     *
     * @return JsonResponse
     */
    public function respondWithErrors($errors, $headers = [])
    {
        $data = [
            'errors' => $errors,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message, int $error_code
     *
     * @return JsonResponse
     */
    public function respondError($message, $error_code)
    {
        return $this->setStatusCode($error_code)->respondWithErrors($message);
    }

    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondUnauthorized($message = 'Not authorized!')
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }

    /**
     * Returns a 422 Unprocessable Entity
     *
     * @param string $message
     * @return JsonResponse
     */
    public function respondValidationError($message = 'Validation errors')
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    /**
     * Returns a 404 Not Found
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    public function respondNotFound($message = 'Not found!')
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }

    /**
     * Set Request Header
     *
     * @return array
     */
    protected function getHeaderDefault() {
        return array(
            "Access-Control-Allow-Origin" => "*",
            "Access-Control-Allow-Headers" => "origin, x-requested-with, Content-Type, accept, Token",
            "Access-Control-Allow-Methods" => "GET,POST,OPTIONS,DELETE,PUT",
            "Content-type" => "application/xml",
            "Content-type" => "application/json; charset=utf-8",
        );
    }


    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function respond($data, $headers = [])
    {
        if(empty($headers)) {
            $headers= $this->getHeaderDefault();
        }

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        return new JsonResponse(
            $serializer->normalize($data, null, array('enable_max_depth' => true)),
            $this->getStatusCode(),
            $headers);
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param array $headers
     *
     * @return JsonResponse
     */
    public function respondFromArrayData($data, $headers = [])
    {
        if(empty($headers)) {
            $headers= $this->getHeaderDefault();
        }

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        return new JsonResponse(
            $data,
            $this->getStatusCode(),
            $headers);
    }


}
