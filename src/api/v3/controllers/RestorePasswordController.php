<?php

namespace yii2module\account\api\v3\controllers;

use yii2lab\rest\domain\rest\Controller;

class RestorePasswordController extends Controller
{
	public $service = 'account.restorePassword';
	
	/**
	 * @inheritdoc
	 */
	protected function verbs()
	{
		return [
			'request' => ['POST'],
			'check-code' => ['POST'],
			'confirm' => ['POST'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'request' => [
				'class' => 'yii2lab\rest\domain\rest\UniAction',
				'successStatusCode' => 201,
				'serviceMethod' => 'request',
				'serviceMethodParams' => ['login'],
			],
			'check-code' => [
				'class' => 'yii2lab\rest\domain\rest\UniAction',
				'successStatusCode' => 204,
				'serviceMethod' => 'checkActivationCode',
				'serviceMethodParams' => ['login', 'activation_code'],
			],
			'confirm' => [
				'class' => 'yii2lab\rest\domain\rest\UniAction',
				'successStatusCode' => 204,
				'serviceMethod' => 'confirm',
				'serviceMethodParams' => ['login', 'activation_code', 'password'],
			],
		];
	}

}