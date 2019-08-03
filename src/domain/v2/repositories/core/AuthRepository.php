<?php

namespace yii2bundle\account\domain\v2\repositories\core;

use Yii;
use yii2rails\extension\core\domain\repositories\base\BaseCoreRepository;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2bundle\account\domain\v2\entities\LoginEntity;
use yii2bundle\account\domain\v2\interfaces\repositories\AuthInterface;

class AuthRepository extends BaseCoreRepository implements AuthInterface {
	
	public $point = 'auth';
	
	public function authentication($login, $password, $ip = null) {
		$response = $this->post(null, compact('login', 'password'), [ClientHelper::IP_HEADER_KEY => $ip]);
		$entity = $this->forgeEntity($response, LoginEntity::class);
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