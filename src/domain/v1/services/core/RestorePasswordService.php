<?php

namespace yii2module\account\domain\v1\services\core;

use yii2lab\domain\helpers\Helper;
use yii2module\account\domain\v1\forms\RestorePasswordForm;
use yii2lab\domain\services\CoreBaseService;

class RestorePasswordService extends CoreBaseService {
	
	public $point = 'auth/restore-password';
	
	public function request($login) {
		$body = compact(['login']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_REQUEST);
		$this->client->post('request', $body);
	}
	
	public function checkActivationCode($login, $activation_code) {
		$body = compact(['login', 'activation_code']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_CHECK);
		$this->client->post('check-code', $body);
	}
	
	public function confirm($login, $activation_code, $password) {
		$body = compact(['login', 'activation_code', 'password']);
		Helper::validateForm(RestorePasswordForm::class, $body, RestorePasswordForm::SCENARIO_CONFIRM);
		$this->client->post('confirm', $body);
	}
	
}
