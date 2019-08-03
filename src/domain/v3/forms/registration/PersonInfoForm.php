<?php

namespace yii2bundle\account\domain\v3\forms\registration;

use yii\base\Model;
use yii2bundle\geo\domain\validators\PhoneValidator;
use yii2bundle\account\domain\v3\validators\PasswordValidator;
use yii2bundle\account\domain\v3\validators\UserBirthdayValidator;
use yii2bundle\account\domain\v3\validators\UserLoginValidator;
use yii2rails\domain\helpers\Helper;
use yii2bundle\account\domain\v3\validators\UserNameValidator;

class PersonInfoForm extends Model {
	
	const SCENARIO_PERSON_INFO = 'SCENARIO_PERSON_INFO';
	const SCENARIO_VERIFY_CODE = 'SCENARIO_VERIFY_CODE';
	const SCENARIO_REQUEST_CODE = 'SCENARIO_REQUEST_CODE';
	const SCENARIO_CREATE_ACCOUNT = 'SCENARIO_CREATE_ACCOUNT';
	
	public $first_name;
	public $last_name;
	public $middle_name;
	public $login;
	public $birthday_day;
	public $birthday_month;
	public $birthday_year;
	public $phone;
	public $password;
	public $password_confirm;
	public $activation_code;
	public $birth_date;
	public $company_id;

	public function rules() {
		return [
            [[
                'first_name', 'last_name', 'login',
                'birthday_day', 'birthday_month', 'birthday_year',
                'phone', 'password', 'password_confirm'], 'trim'],
            [[
                'first_name', 'last_name', 'login',
                'birthday_day', 'birthday_month', 'birthday_year',
                'phone', 'password', 'password_confirm'], 'required'],

            [['first_name', 'last_name'], 'string', 'min' => 2],

            ['birthday_day', 'integer', 'min' => 1, 'max' => 31],
            ['birthday_month', 'integer', 'min' => 1, 'max' => 12],
            ['birthday_year', 'integer', 'min' => 1800, 'max' => intval(date('Y'))],
            ['birthday_year', UserBirthdayValidator::class],
            [['first_name', 'last_name', 'middle_name'], UserNameValidator::class],
			['login', UserLoginValidator::class],
			['phone', PhoneValidator::class],
			['password', PasswordValidator::class],
			
            ['password_confirm', 'compare', 'compareAttribute' => 'password'],
			
			[['activation_code'], 'integer'],
			[['activation_code'], 'string', 'length' => 6],
		];
	}
	
	public function scenarios() {
		return [
			self::SCENARIO_PERSON_INFO => ['first_name', 'last_name', 'middle_name', 'login',
				'birthday_day', 'birthday_month', 'birthday_year',
				'phone', 'password', 'password_confirm'],
			self::SCENARIO_VERIFY_CODE => ['phone', 'activation_code'],
			self::SCENARIO_REQUEST_CODE => ['phone'],
			self::SCENARIO_CREATE_ACCOUNT => ['first_name', 'last_name', 'middle_name', 'login',
				'birthday_day', 'birthday_month', 'birthday_year',
				'phone', 'password', 'password_confirm',
				'activation_code',
			],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
        $labels = Helper::forgeLabels([
            'first_name', 'last_name', 'login',
            'birthday_day', 'birthday_month', 'birthday_year',
            'phone',
        ], 'user/person');
		$labels = Helper::forgeLabels(['activation_code'], 'user/registration', $labels);
        $labels = Helper::forgeLabels(['password', 'password_confirm'], 'user/account', $labels);
		return $labels;
	}

}
