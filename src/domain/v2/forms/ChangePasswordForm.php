<?php

namespace yii2module\account\domain\v2\forms;

use Yii;
use yii2lab\domain\base\Model;

class ChangePasswordForm extends Model
{
	public $new_password;
	public $password;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['password', 'new_password'], 'trim'],
			[['password', 'new_password'], 'required'],
			[['password', 'new_password'], 'string', 'min' => 8],
			['new_password', 'compare', 'compareAttribute' => 'password', 'operator' => '!='],
		];
	}
	
	public function attributeLabels()
	{
		return [
			'password' => Yii::t('account/main', 'password'),
			'new_password'=> Yii::t('account/security', 'new_password'),
		];
	}
	
}
