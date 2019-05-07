<?php

namespace yii2module\account\domain\v3\forms\restorePassword;

use Yii;
use yii\base\Model;
use yii2lab\geo\domain\validators\PhoneValidator;
use yii2module\account\domain\v3\validators\PasswordValidator;
use yii2rails\domain\helpers\Helper;

class UpdatePasswordForm extends Model {
	
	const SCENARIO_REQUEST_CODE = 'SCENARIO_REQUEST_CODE';
	const SCENARIO_VERIFY_CODE = 'SCENARIO_VERIFY_CODE';
	const SCENARIO_SET_PASSWORD = 'SCENARIO_SET_PASSWORD';
	
	public $phone;
	public $activation_code;
    public $password;
    public $password_confirm;

	public function rules() {
		return [
			[['password', 'password_confirm', 'phone'], 'trim'],
			[['password', 'password_confirm', 'phone'], 'required'],
			
			[['activation_code'], 'integer'],
			[['activation_code'], 'string', 'length' => 6],
			
			['phone', PhoneValidator::class],
			//[['password'], 'string', 'min' => 6],
			['password', PasswordValidator::class],

            ['password_confirm', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('user/restore-password', 'passwords_dont_match')],
		];
	}
	
	public function scenarios() {
		return [
			self::SCENARIO_REQUEST_CODE => ['phone'],
			self::SCENARIO_VERIFY_CODE => ['phone', 'activation_code'],
			self::SCENARIO_SET_PASSWORD => ['phone', 'activation_code', 'password', 'password_confirm'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
        $labels = Helper::forgeLabels(['password', 'password_confirm'], 'user/account');
		$labels = Helper::forgeLabels(['activation_code'], 'user/registration', $labels);
		$labels = Helper::forgeLabels([
			'phone',
		], 'user/person', $labels);
		return $labels;
	}

}
