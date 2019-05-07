<?php

namespace yii2module\account\domain\v3;

use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\jwt\filters\token\JwtFilter;
use yii2module\account\domain\v3\enums\AccountRoleEnum;
use yii2rails\domain\enums\Driver;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2module\account\domain\v3\filters\login\LoginValidator;
use yii2module\account\domain\v3\filters\token\DefaultFilter;
use yii2module\account\domain\v3\interfaces\services\LoginInterface;
use yii2module\account\domain\v3\interfaces\services\RegistrationInterface;
use yii2module\account\domain\v3\interfaces\services\RestorePasswordInterface;
use yii2module\account\domain\v3\services\SocketIOService;

// todo: описание докблоков в руководство

/**
 * Class Domain
 * 
 * @package yii2module\account\domain\v3
 * @property-read \yii2module\account\domain\v3\interfaces\services\AuthInterface $auth
 * @property-read \yii2module\account\domain\v3\interfaces\services\LoginInterface $login
 * @property-read \yii2module\account\domain\v3\interfaces\services\RegistrationInterface $registration
 * @property-read \yii2module\account\domain\v3\interfaces\services\TempInterface $temp
 * @property-read \yii2module\account\domain\v3\interfaces\services\RestorePasswordInterface $restorePassword
 * @property-read \yii2module\account\domain\v3\interfaces\services\SecurityInterface $security
 * @property-read \yii2module\account\domain\v3\interfaces\services\TestInterface $test
 * @property-read \yii2module\account\domain\v3\interfaces\services\RbacInterface $rbac
 * @property-read \yii2module\account\domain\v3\interfaces\services\ConfirmInterface $confirm
 * @property-read \yii2module\account\domain\v3\interfaces\repositories\RepositoriesInterface $repositories
 * @property-read \yii2module\account\domain\v3\interfaces\services\TokenInterface $token
 * @property-read \yii2module\account\domain\v3\interfaces\services\JwtInterface $jwt
 * @property-read \yii2module\account\domain\v3\interfaces\services\ActivityInterface $activity
 * @property-read \yii2module\account\domain\v3\interfaces\services\OauthInterface $oauth
 * @property-read \yii2module\account\domain\v3\interfaces\services\SocketInterface $socket
 * @property-read \yii2module\account\domain\v3\interfaces\services\SocketInterface $socketio
 * @property-read \yii2module\account\domain\v3\interfaces\services\IdentityInterface $identity
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {

		$remoteServiceDriver = $this->primaryDriver == Driver::CORE ? Driver::CORE : null;
		//$serviceNamespace = $this->primaryDriver == Driver::CORE ? 'yii2module\account\domain\v3\services\core' : 'yii2module\account\domain\v3\services';
		if(EnvService::getServer('core.host')) {
            $remoteServiceDriver = Driver::CORE;
            $remoteRepositoryDriver = Driver::CORE;
        } else {
            $remoteServiceDriver = null;
            $remoteRepositoryDriver = Driver::ACTIVE_RECORD;
        }

        //$remoteRepositoryDriver = 'ldap';

		return [
			'repositories' => [
				'auth' => $remoteRepositoryDriver,
				'login' => $this->primaryDriver,
				//'temp' => Driver::ACTIVE_RECORD,
				'restorePassword' => $this->primaryDriver,
				'security' => $this->primaryDriver,
				'test' => Driver::FILEDB,
				//'rbac' => Driver::MEMORY,
				'confirm' => Driver::ACTIVE_RECORD,
				//'assignment' => $this->primaryDriver,
				'token' => Driver::ACTIVE_RECORD,
                'jwt' => 'jwt',
				'activity' => Driver::ACTIVE_RECORD,
                'identity' => Driver::ACTIVE_RECORD,
			],
			'services' => [
				'auth' => [
					'rememberExpire' => TimeEnum::SECOND_PER_YEAR,
					'tokenAuthMethods' => [
						'bearer' => DefaultFilter::class,
						'jwt' => [
							'class' => JwtFilter::class,
							'profile' => 'auth',
						],
					],
				],
				'login' => [
					'defaultRole' => AccountRoleEnum::UNKNOWN_USER,
					'defaultStatus' => 1,
					'forbiddenStatusList' => [0],
					'loginValidator' => LoginValidator::class,
				],
				'registration' => $remoteServiceDriver, //$serviceNamespace . '\RegistrationService',
				//'temp',
				'restorePassword' => $remoteServiceDriver,
				'security',
				'test',
				//'rbac',
				'confirm',
				//'assignment',
				'token',
                'jwt',
				'activity',
				'oauth',
                'socket',
                'socketio' => SocketIOService::class,
                //TODO: либо прописывать вот так, если не хотим явно указывать класс, но тогда и во FlowService надо менять
                //'socketIO',
                'identity',
			],
		];
	}
	
}