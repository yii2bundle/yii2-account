<?php

namespace yii2module\account\domain\v2\validators;

use App;
use Yii;
use yii\validators\StringValidator;
use yii2rails\extension\validator\BaseValidator;

class PasswordValidator extends BaseValidator {
	
	protected $messageLang = ['account/login', 'not_valid'];
	
	public function validateAttribute($model, $attribute) {
		$lowerCharExists = preg_match('#[a-z]+#', $model->$attribute);
		$upperCharExists = preg_match('#[A-Z]+#', $model->$attribute);
		$numericExists = preg_match('#[A-Z]+#', $model->$attribute);
		
		/*$v = new StringValidator;
		$v->min = 6;
		$v->validateAttribute($model, $attribute);*/
		
		$isValid = $lowerCharExists && $upperCharExists && $numericExists;
		if(!$isValid) {
			$this->addError($model, $attribute, Yii::t('account/main', 'bad_password'));
		}
	}
	
}
