<?php

namespace yii2bundle\account\domain\v2\interfaces\services;

interface RestorePasswordInterface {
	
	public function request($login, $mail = null);
	public function checkActivationCode($login, $activation_code);
	public function confirm($login, $activation_code, $password);

}