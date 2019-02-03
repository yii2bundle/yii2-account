<?php

namespace yii2module\account\domain\v2\forms;

use Yii;
use yii2module\account\domain\v2\validators\LoginValidator;
use yii2rails\domain\base\Model;

class RestorePasswordForm extends Model {
	
	public $login;
	public $activation_code;
	public $password;
	
	const SCENARIO_REQUEST = 'request';
	const SCENARIO_CHECK = 'check';
	const SCENARIO_CONFIRM = 'confirm';
	
	public function rules() {
		return [
			[['login', 'password', 'activation_code'], 'trim'],
			[['login', 'password', 'activation_code'], 'required'],
			['login', LoginValidator::class],
			[['activation_code'], 'integer'],
			[['activation_code'], 'string', 'length' => 6],
			[['password'], 'string', 'min' => 8],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'login' 		=> Yii::t('account/main', 'login'),
			'password' 		=> Yii::t('account/main', 'password'),
			'activation_code' 		=> Yii::t('account/main', 'activation_code'),
		];
	}
	
	public function scenarios() {
		return [
			self::SCENARIO_REQUEST => ['login'],
			self::SCENARIO_CHECK => ['login', 'activation_code'],
			self::SCENARIO_CONFIRM => ['login', 'activation_code', 'password'],
		];
	}
	
}
