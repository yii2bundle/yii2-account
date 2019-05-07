<?php

namespace yii2module\account\domain\v3\repositories\core;

use Yii;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\helpers\RestHelper;
use yii2rails\extension\core\domain\repositories\base\BaseCoreRepository;
use yii2rails\extension\web\enums\HttpHeaderEnum;
use yii2rails\extension\web\enums\HttpMethodEnum;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2module\account\domain\v3\entities\LoginEntity;
use yii2module\account\domain\v3\interfaces\repositories\AuthInterface;

class AuthRepository extends BaseCoreRepository implements AuthInterface {
	
	public $point = 'auth';
	public $version = 1;
	
	public function authentication($login, $password, $ip = null) {
		$responseEntity = $this->post(null, compact('login', 'password'), [ClientHelper::IP_HEADER_KEY => $ip]);
		$entity = $this->forgeEntity($responseEntity, LoginEntity::class);
		return $entity;
	}

    public function authenticationByToken($token, $type = null, $ip = null) {
	    $requestEntity = new RequestEntity;
        $requestEntity->method = HttpMethodEnum::GET;
	    $requestEntity->uri = $this->baseUrl;
        $requestEntity->headers = [
            HttpHeaderEnum::AUTHORIZATION => $token,
        ];
	    $responseEntity = RestHelper::sendRequest($requestEntity);
        //d($responseEntity);
        //$responseEntity = $this->get(null, [], [ClientHelper::IP_HEADER_KEY => $ip]);

        $entity = $this->forgeEntity($responseEntity->data, LoginEntity::class);
        return $entity;
    }
	
	public function forgeEntity($data, $class = null) {
		/** @var LoginEntity $entity */
		$entity = parent::forgeEntity($data, $class);
		if(empty($entity->status)) {
			$entity->status = \App::$domain->account->login->defaultStatus;
		}
		return $entity;
	}
}