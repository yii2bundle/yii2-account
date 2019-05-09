<?php

namespace yii2module\account\domain\v3\repositories\ar;

use yii2module\account\domain\v3\helpers\AuthHelper;
use yii2module\account\domain\v3\interfaces\repositories\AuthInterface;
use yii2module\account\domain\v3\repositories\base\BaseAuthRepository;
use yii2rails\domain\repositories\BaseRepository;
use yii\base\InvalidArgumentException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2module\account\domain\v3\helpers\LoginTypeHelper;
use yii2rails\domain\data\Query;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v3\entities\LoginEntity;
use yii2module\account\domain\v3\entities\SecurityEntity;
use yii2module\account\domain\v3\helpers\TokenHelper;

class AuthRepository extends BaseRepository implements AuthInterface {
	
	public function authentication($login, $password, $ip = null) {
		try {
			$query = new Query;
			$query->with('assignments');
			//$query->with('person');
			/*if(\App::$domain->has('staff')) {
				$query->with('company');
			}*/
			
			$loginEntity = \App::$domain->account->login->oneByAny($login, $query);
			//AuthHelper::setToken($tokenDto->token);
			//$loginEntity = $this->domain->repositories->login->oneByVirtual($login, $query);
		} catch(NotFoundHttpException $e) {
			return false;
		}
		if(empty($loginEntity)) {
			return false;
		}
		$isValidPassword = $this->domain->security->isValidPassword($loginEntity->id, $password);
		if($isValidPassword) {
			$loginEntity->token = $this->domain->token->forge($loginEntity->id, $ip);
			return $loginEntity;
		}
		return false;
	}
	
	public function authenticationByToken($token/*, $type = null, $ip = null*/) {
		if(empty($token)) {
			throw new InvalidArgumentException('Empty token');
		}
		if(!LoginTypeHelper::isToken($token)) {
			throw new InvalidArgumentException('Invalid token');
		}
		$query = new Query;
		$query->with('assignments');
		try {
			$loginEntity = \App::$domain->account->login->oneByAny($token, $query);
		} catch(\Exception $e) {
			throw new UnauthorizedHttpException($e->getMessage(), 0, $e);
		}
		AuthHelper::setToken($token);
		return $loginEntity;
	}
	
}
