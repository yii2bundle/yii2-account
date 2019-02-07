<?php

namespace yii2module\account\domain\v3\filters\login;

use yii2module\account\domain\v3\helpers\LoginHelper;
use yii2module\account\domain\v3\interfaces\LoginValidatorInterface;

class LoginValidator implements LoginValidatorInterface {
	
	public function normalize($value) : string {
		return LoginHelper::pregMatchLogin($value);
	}
	
	public function isValid($value) : bool {
		return LoginHelper::validate($value);
	}
}
