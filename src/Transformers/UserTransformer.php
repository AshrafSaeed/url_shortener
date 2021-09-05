<?php
namespace App\Transformers;

use Symfony\Component\HttpFoundation\Request;

class UserTransformer
{

    /**
     * Transform URL single item
     * 
     * @param Entity Object $objData
     * @return array
    */
    public static function user($objData, $token=null) : array
    {
    	if(empty($objData)) {
    		return null;
    	}

    	return [
    		'id' => $objData->getId(),
    		'name' => $objData->getName(),
    		'email' => $objData->getEmail(),
            'token' => $token,
    		'created_at' => $objData->getCreatedAt()->format('Y-m-d H:i:s'),
    		'updated_at' => $objData->getUpdatedAt()->format('Y-m-d H:i:s'),
    	];
    }

}
