<?php

namespace yii2bundle\account\api\v1\controllers;

use yii2lab\rest\domain\rest\Controller;
use yii2rails\extension\web\helpers\Behavior;

class BalanceController extends Controller
{

	public $service = 'account.balance';

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'authenticator' => Behavior::auth(),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'index' => [
				'class' => 'yii2lab\rest\domain\rest\IndexActionWithQuery',
				'serviceMethod' => 'oneSelf',
			],
		];
	}
	
}