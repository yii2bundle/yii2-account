<?php

namespace yii2module\account\domain\v1\services\core;

use yii2lab\domain\helpers\Helper;
use yii2lab\extension\core\domain\services\base\BaseCoreService;
use yii2module\account\domain\v1\forms\RegistrationForm;

class RegistrationService extends BaseCoreService {
	
	public $point = 'registration';
	
	public function createTempAccount($login, $email = null) {
		$body = compact('login', 'email');
		Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_REQUEST);
		$this->client->post('create-account', $body);
	}
	
	public function checkActivationCode($login, $activation_code) {
		$body = compact('login', 'activation_code');
		Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CHECK);
		$this->client->post('activate-account', $body);
	}
	
	public function activateAccount($login, $activation_code) {
		$body = compact('login', 'activation_code');
		Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CHECK);
		$this->client->post('activate-account', $body);
	}
	
	public function createTpsAccount($login, $activation_code, $password, $email = null) {
		$body = compact('login', 'activation_code', 'password');
		Helper::validateForm(RegistrationForm::class, $body, RegistrationForm::SCENARIO_CONFIRM);
		$this->client->post('set-password', $body);
	}

}
