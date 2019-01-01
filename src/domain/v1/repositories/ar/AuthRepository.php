<?php

namespace yii2module\account\domain\v1\repositories\ar;

use yii\web\NotFoundHttpException;
use yii2module\account\domain\v1\interfaces\repositories\AuthInterface;
use yii2lab\domain\repositories\BaseRepository;
use Yii;

class AuthRepository extends BaseRepository implements AuthInterface {
	
	public function authentication($login, $password) {
		try {
			$user = $this->domain->getRepositories()->login->oneByLogin($login);
		} catch(NotFoundHttpException $e) {
			return false;
		}
		if(empty($user)) {
			return false;
		}
		if(!Yii::$app->security->validatePassword($password, $user->password_hash)) {
			return false;
		}
		return $user;
	}
	
	public function updateBalance() {
	
	}
	
	public function getBalance() {
		$balance = [];
		return $this->domain->factory->entity->create('balance', $balance);
	}
	
	public function setToken($token) {
	
	}
	
}