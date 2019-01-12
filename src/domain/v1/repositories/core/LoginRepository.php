<?php

namespace yii2module\account\domain\v1\repositories\core;

use yii\helpers\ArrayHelper;
use yii\rbac\Assignment;
use yii\web\NotFoundHttpException;
use yii2lab\extension\core\domain\repositories\base\BaseActiveCoreRepository;
use yii2module\account\domain\v1\helpers\LoginEntityFactory;
use yii2module\account\domain\v1\interfaces\repositories\LoginInterface;

class LoginRepository extends BaseActiveCoreRepository implements LoginInterface {
	
	public $baseUri = 'user';
	public $version = 'v4';
	
	public function isExistsByLogin($login) {
		try {
			$this->oneByLogin($login);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	public function oneByLogin($login) {
		$response = $this->get($login);
		return $this->forgeEntity($response->data);
	}
	
	public function oneByToken($token, $type = null) {
		$response = $this->get('/auth');
		return $this->forgeEntity($response->data);
	}
	
	public function forgeEntity($user, $class = null) {
		if(empty($user)) {
			return null;
		}
		$user = ArrayHelper::toArray($user);
		$user = $this->alias->decode($user);
		return LoginEntityFactory::forgeLoginEntity($user);
	}
}