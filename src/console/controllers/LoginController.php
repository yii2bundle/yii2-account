<?php

namespace yii2module\account\console\controllers;

use Yii;
use yii2lab\extension\console\helpers\Output;
use yii2lab\extension\console\base\Controller;
use yii2lab\extension\console\helpers\input\Enter;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\console\forms\LoginForm;

class LoginController extends Controller
{
	
	public function actionCreate()
	{
		$data = Enter::form(LoginForm::class);
		try {
			$entity = \App::$domain->account->login->create($data);
			Output::arr($entity->toArray());
			Output::line('Success created!');
		} catch(UnprocessableEntityHttpException $e) {
			Output::line('Create FAIL');
		}
	}
	
}
