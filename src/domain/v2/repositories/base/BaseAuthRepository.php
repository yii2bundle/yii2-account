<?php

namespace yii2module\account\domain\v2\repositories\base;

use yii\web\NotFoundHttpException;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\entities\SecurityEntity;
use yii2module\account\domain\v2\interfaces\repositories\AuthInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class BaseAuthRepository
 *
 * @package yii2module\account\domain\v2\repositories\base
 * @property \yii2module\account\domain\v2\Domain $domain
 */
class BaseAuthRepository extends BaseRepository implements AuthInterface {
	
	public function authentication($login, $password, $ip = null) {
		try {
			/** @var LoginEntity $loginEntity */
			$loginEntity = \App::$domain->account->repositories->identity->oneByLogin($login);
		} catch(NotFoundHttpException $e) {
			return false;
		}
		if(empty($loginEntity)) {
			return false;
		}
		/** @var SecurityEntity $securityEntity */
		try {
			$securityEntity = \App::$domain->account->repositories->security->validatePassword($loginEntity->id, $password);
		} catch(UnprocessableEntityHttpException $e) {
			return false;
		}
		$securityEntity->token = \App::$domain->account->token->forge($loginEntity->id, $ip);
		$loginEntity->security = $securityEntity;
		return $loginEntity;
	}
	
}