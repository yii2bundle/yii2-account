<?php

namespace yii2module\account\domain\v1\interfaces\repositories;

interface RestorePasswordInterface {
	
	public function requestNewPassword($login, $mail = null);
	public function checkActivationCode($login, $code);
	public function setNewPassword($login, $code, $password);
	public function isExists($login);

}