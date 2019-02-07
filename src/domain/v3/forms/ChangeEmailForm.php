<?php

namespace yii2module\account\domain\v3\forms;

use Yii;
use yii2rails\domain\base\Model;

class ChangeEmailForm extends Model
{
	public $email;
	public $password;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['password', 'email'], 'trim'],
			[['password', 'email'], 'required'],
			[['password'], 'string', 'min' => 8],
			['email', 'email'],
		];
	}
	
	
	public function attributeLabels()
	{
		return [
			'password' => Yii::t('account/main', 'password'),
			'email' => Yii::t('account/main', 'email'),
		];
	}
	
}
