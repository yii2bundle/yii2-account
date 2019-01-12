<?php

namespace yii2module\account\api\v2\controllers;

use yii2lab\rest\domain\rest\Controller;
use yii2lab\extension\web\helpers\Behavior;

class SecurityController extends Controller
{

	public $service = 'account.security';

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'authenticator' => Behavior::auth(),
			'verb' => Behavior::verb([
				'email' => ['PUT'],
				'password' => ['PUT'],
			]),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions() {
		$actions = parent::actions();
		$actions['email'] = [
			'class' => 'yii2lab\rest\domain\rest\UniAction',
			'successStatusCode' => 204,
			'serviceMethod' => 'changeEmail',
		];
		$actions['password'] = [
			'class' => 'yii2lab\rest\domain\rest\UniAction',
			'successStatusCode' => 204,
			'serviceMethod' => 'changePassword',
		];
		return $actions;
	}

}