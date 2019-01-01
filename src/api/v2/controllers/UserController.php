<?php

namespace yii2module\account\api\v2\controllers;

use yii2lab\extension\web\helpers\Behavior;
use yii2lab\rest\domain\rest\ActiveController as Controller;

class UserController extends Controller
{

	public $service = 'account.login';

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			//'cors' => Behavior::cors(),
			//'authenticator' => Behavior::auth(['view']),
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'create' => [
				'class' => 'yii2lab\domain\rest\CreateAction',
			],
			'view' => [
				'class' => 'yii2lab\domain\rest\ViewActionWithQuery',
			],
		];
	}

}