<?php

namespace yii2module\account\domain\v3\helpers;

use Yii;
use yii2rails\domain\helpers\DomainHelper;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2module\account\domain\v3\entities\LoginEntity;
use yii2module\account\domain\v3\forms\LoginForm;

class TestAuthHelper {
	
	const ADMIN_PASSWORD = 'Wwwqqq111';
	const DOMAIN_CLASS = \yii2module\account\domain\v3\Domain::class;
	
	public static function authByLogin($login, $password = self::ADMIN_PASSWORD) {
        $loginForm = new LoginForm;
        $loginForm->login = $login;
        $loginForm->password = $password;
        $loginEntity = \App::$domain->account->auth->authenticationFromApi($loginForm);
		Yii::$app->user->setIdentity($loginEntity);
	}
	
	public static function authById($id) {
		/** @var LoginEntity $userEntity */
		$userEntity = \App::$domain->account->login->oneById($id);
		Yii::$app->user->setIdentity($userEntity);
	}

}
