<?php

namespace yii2module\account\domain\v3\validators;

use Yii;
use yii\validators\StringValidator;
use yii2rails\extension\validator\BaseValidator;

class PasswordValidator extends BaseValidator {

    public $min = 6;
    public $max = 18;
	protected $messageLang = ['account/login', 'not_valid'];
	
	public function validateAttribute($model, $attribute) {
        $model->$attribute = trim($model->$attribute);
        $this->preValidate($model, $attribute);
		$lowerCharExists = preg_match('#[a-z]+#', $model->$attribute);
		$upperCharExists = preg_match('#[A-Z]+#', $model->$attribute);
		$numericExists = preg_match('#[0-9]+#', $model->$attribute);
        //$isMach = preg_match('#^[a-zA-Z0-9-_!]+$#', $model->$attribute);
		$isValid = $lowerCharExists && $upperCharExists && $numericExists;
		if(!$isValid) {
			$this->addError($model, $attribute, Yii::t('account/main', 'bad_password'));
		}
	}

	private function preValidate($model, $attribute) {
        $validator = Yii::createObject([
            'class' => StringValidator::class,
            'min' => $this->min,
            'max' => $this->max,
        ]);
        $validator->validateAttribute($model, $attribute);
    }
	
}
