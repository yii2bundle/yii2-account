<?php

namespace yii2module\account\module\forms;

use Yii;
use yii2rails\domain\base\Model;

class SetSecurityForm extends Model
{
	
	public $email;
	public $email_repeat;
	public $password;
	public $password_repeat;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['email', 'email_repeat', 'password', 'password_repeat'], 'trim'],
			[['email',  'password', 'password_repeat'], 'required'],
			['email', 'email'],
			['password', 'string', 'min' => 6],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'password' 		=> Yii::t('account/main', 'password'),
			'password_repeat' 		=> Yii::t('account/main', 'password_repeat'),
			'email' 		=> Yii::t('account/main', 'email'),
		];
	}
	
}
