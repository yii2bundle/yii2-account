<?php

namespace yii2bundle\account\domain\v2\validators;

use yii2rails\extension\validator\BaseValidator;
use yii2bundle\account\domain\v2\helpers\LoginHelper;

class LoginValidator extends BaseValidator {
	
	protected $messageLang = ['account/login', 'not_valid'];
	
	protected function validateValue($value) {
		$isValid = LoginHelper::validate($value);
		return $this->prepareMessage($isValid);
	}
	
}
