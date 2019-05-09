<?php

namespace yii2module\account\api\v3\actions\registration;

use Yii;
use yii2lab\rest\domain\rest\UniAction;
use yii2module\account\domain\v3\exceptions\ConfirmAlreadyExistsException;

class CreateAccountAction extends UniAction {
	
	public function run() {
		$login = Yii::$app->request->getBodyParam('login');
		$email = Yii::$app->request->getBodyParam('email');
		
		try {
			\App::$domain->account->registration->createTempAccount($login, $email);
			Yii::$app->response->setStatusCode($this->successStatusCode);
		} catch(ConfirmAlreadyExistsException $e) {
			Yii::$app->response->setStatusCode(202);
		}
	}
	
}