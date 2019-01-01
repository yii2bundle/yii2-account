<?php

namespace yii2module\account\domain\v2\interfaces\services;

interface RegistrationInterface {
	
	public function createTempAccount($login, $email = null);
	public function checkActivationCode($login, $activation_code);
	public function activateAccount($login, $activation_code);
	public function createTpsAccount($login, $activation_code, $password, $email = null);

}