<?php
namespace App\Transformers;

class UrlTransformer
{

    /**
     * Transform URL list
     * 
     * @param array $arrData
     * @return array
    */

    public static function list(array $arrData) : array
    {
    	if(!is_array($arrData)) {
    		return null;
    	}

    	$transformedData = [];
    	foreach($arrData as $row){
    		$transformedData[] = self::url($row);
    	}

    	return $transformedData;
    }

    /**
     * Transform URL single item
     * 
     * @param Entity Object $objData
     * @return array
    */
    public static function url($objData) : array
    {
    	if(empty($objData)) {
    		return null;
    	}

    	return [
    		'id' => $objData->getId(),
    		'full_url' => $objData->getFullUrl(),
    		'url_token' => $objData->getUrlToken(),
    		'public_url' => $_SERVER['HTTP_HOST'].'/'.$objData->getUrlToken(),
    		'created_at' => $objData->getCreatedAt()->format('Y-m-d H:i:s'),
    		'updated_at' => $objData->getUpdatedAt()->format('Y-m-d H:i:s'),
    	];
    }

}
