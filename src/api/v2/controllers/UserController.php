<?php

namespace yii2bundle\account\api\v2\controllers;

use yii2rails\extension\web\helpers\Behavior;
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
				'class' => 'yii2lab\rest\domain\rest\CreateAction',
			],
			'view' => [
				'class' => 'yii2lab\rest\domain\rest\ViewActionWithQuery',
			],
		];
	}

}