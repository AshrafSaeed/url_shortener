<?php

namespace App\Validations;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UrlValidation
{

   /**
     * Validate URL Rule
     * 
     * @return Assert\Collection
    */

    public static function urlRule()
    {
        return new Assert\Collection([
            'url' => [new Assert\notBlank(['message' => 'url value should not blank']), new Assert\Url(['message' => 'please enter value url (e.g http://example.com)'])],
        ]);
        
    }

    /**
     * Extrect Validation Error
     * 
     * @return array
    */

    public static function urlErrors($errors=[])
    {   
        $arrErrors = [];                    
        foreach($errors as $error){
            $arrErrors[] = $error->getMessage();
        }

        return $arrErrors;
    }

}
