<?php

namespace yii2bundle\account\domain\v2;

use yii2rails\extension\jwt\filters\token\JwtFilter;
use yii2bundle\account\domain\v2\enums\AccountRoleEnum;
use yii2rails\domain\enums\Driver;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2bundle\account\domain\v2\filters\login\LoginValidator;
use yii2bundle\account\domain\v2\filters\token\DefaultFilter;

// todo: описание докблоков в руководство

/**
 * Class Domain
 * 
 * @package yii2bundle\account\domain\v2
 * @property-read \yii2bundle\account\domain\v2\interfaces\services\AuthInterface $auth
 * @property-read \yii2bundle\account\domain\v2\interfaces\services\LoginInterface $login
 * @property-read \yii2bundle\account\domain\v2\interfaces\services\RegistrationInterface $registration
 * @property-read \yii2bundle\account\domain\v2\interfaces\services\TempInterface $temp
 * @property-read \yii2bundle\account\domain\v2\interfaces\services\RestorePasswordInterface $restorePassword
 * @property-read \yii2bundle\account\domain\v2\interfaces\services\SecurityInterface $security
 * @property-read \yii2bundle\account\domain\v2\interfaces\services\TestInterface $test
 * @property-read \yii2bundle\account\domain\v2\interfaces\services\RbacInterface $rbac
 * @property-read \yii2bundle\account\domain\v2\interfaces\services\ConfirmInterface $confirm
 * @property-read \yii2bundle\account\domain\v2\interfaces\repositories\RepositoriesInterface $repositories
 * @property-read \yii2bundle\account\domain\v2\interfaces\services\TokenInterface $token
 * @property-read \yii2bundle\account\domain\v2\interfaces\services\JwtInterface $jwt
 * @property-read \yii2bundle\account\domain\v2\interfaces\services\ActivityInterface $activity
 * @property-read \yii2bundle\account\domain\v2\interfaces\services\OauthInterface $oauth
 */
class Domain extends \yii2rails\domain\Domain {
	
	public function config() {
		$remoteServiceDriver = $this->primaryDriver == Driver::CORE ? Driver::CORE : null;
		$serviceNamespace = $this->primaryDriver == Driver::CORE ? 'yii2bundle\account\domain\v2\services\core' : 'yii2bundle\account\domain\v2\services';
		return [
			'repositories' => [
				'auth' => $this->primaryDriver,
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
				'restorePassword' => $serviceNamespace . '\RestorePasswordService',
				'security',
				'test',
				//'rbac',
				'confirm',
				//'assignment',
				'token',
                'jwt',
				'activity',
				'oauth',
			],
		];
	}
	
}