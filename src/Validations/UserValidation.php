<?php

namespace App\Validations;

use Symfony\Component\Validator\Constraints as Assert;

class UserValidation
{

    /**
     * Validate Login Rule
     * 
     * @return Assert\Collection
    */

    public static function loginRule()
    {
        return new Assert\Collection([
            'email' => [new Assert\notBlank(['message' => 'please enter email address']), new Assert\Email(['message' => 'invalid email'])],
            'password' => [new Assert\NotBlank(['message' => 'please enter password']), new Assert\Length(['min' => 8])],
        ]);
        
    }


    /**
     * Validate Registeration Rule
     * 
     * @return Assert\Collection
    */

    public static function registerationRule()
    {
        return new Assert\Collection([
            'name' => [new Assert\notBlank(['message' => 'please enter the name'])],
            'email' => [new Assert\notBlank(['message' => 'please enter the email']), new Assert\Email(['message' => 'invalid email'])],
            'password' => [new Assert\notBlank(['message' => 'please enter password']), new Assert\Length(['min' => 8])],
        ]);
        
    }
    /**
     * Extrect Validation Error
     * 
     * @return array
    */

    public static function loginErrors($errors=[])
    {   
        $arrErrors = [];                    
        foreach($errors as $error){
            $arrErrors[] = $error->getMessage();
        }

        return $arrErrors;
    }

}
