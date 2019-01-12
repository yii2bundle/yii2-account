<?php

namespace yii2module\account\domain\v1\services;

use yii2lab\domain\helpers\Helper;
use yii2module\account\domain\v1\forms\RestorePasswordForm;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\services\base\BaseService;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;

class RestorePasswordService extends BaseService {

    public $tokenExpire = 300;

	public function request($login, $mail = null) {
		$body = compact(['login']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_REQUEST);
		$this->validateLogin($login);
		$this->repository->requestNewPassword($login, $mail);
	}
	
	public function checkActivationCode($login, $activation_code) {
		$body = compact(['login', 'activation_code']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_CHECK);
		$this->validateLogin($login);
		$this->verifyActivationCode($login, $activation_code);
	}
	
	public function confirm($login, $activation_code, $password) {
		$body = compact(['login', 'activation_code', 'password']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_CONFIRM);
		$this->validateLogin($login);
		$this->verifyActivationCode($login, $activation_code);
		$this->repository->setNewPassword($login, $activation_code, $password);
	}
	
	protected function validateLogin($login) {
		$user = $this->repository->isExists($login);
		if(empty($user)) {
			$error = new ErrorCollection();
			$error->add('login', 'account/main', 'login_not_found');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
	protected function verifyActivationCode($login, $activation_code) {
		$isChecked = $this->repository->checkActivationCode($login, $activation_code);
		if(!$isChecked) {
			$error = new ErrorCollection();
			$error->add('activation_code', 'account/password', 'invalid_activation_code');
			throw new UnprocessableEntityHttpException($error);
		}
	}
	
}
