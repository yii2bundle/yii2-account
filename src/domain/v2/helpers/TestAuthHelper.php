<?php

namespace yii2module\account\domain\v2\helpers;

use Yii;
use yii2rails\domain\helpers\DomainHelper;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\forms\LoginForm;

class TestAuthHelper {
	
	const ADMIN_PASSWORD = 'Wwwqqq111';
	const DOMAIN_CLASS = \yii2module\account\domain\v2\Domain::class;
	
	public static function authPseudo($login, $email = null, $parentLogin = null) {
		self::defineAccountDomain();
		$email = $email ? $email : $login . '@mail.ru';
		$userEntity = \App::$domain->account->authPseudo->authentication($login, ClientHelper::ip(), $email, $parentLogin);
		Yii::$app->user->setIdentity($userEntity);
	}
	
	public static function authByLogin($login, $password = self::ADMIN_PASSWORD) {
		self::defineAccountDomain();
		$userEntity = \App::$domain->account->auth->authentication($login, $password);
		Yii::$app->user->setIdentity($userEntity);
	}
	
	public static function authById($id) {
		self::defineAccountDomain();
		/** @var LoginEntity $userEntity */
		$userEntity = \App::$domain->account->login->oneById($id);
		Yii::$app->user->setIdentity($userEntity);
	}

    public static function defineAccountDomain() {
	    DomainHelper::defineDomains([
		    'account' => self::DOMAIN_CLASS,
	    ]);
    }
	
}
