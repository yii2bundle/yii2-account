<?php

namespace tests\functional\v3\enums;

use PHPUnit\Framework\Constraint\IsType;
use yii2rails\extension\enum\base\BaseEnum;

class LoginEnum extends BaseEnum {
	
	const ID_ADMIN = 1;
	const ID_TESTER_1 = 2;
	const ID_TESTER_2 = 3;
	const ID_NOT_EXISTS = 1234567;
	
	const LOGIN_ADMIN = 'admin';
	const LOGIN_NOT_EXISTS = 'qwerty';
	
	//const TOKEN_ADMIN = '4f6bbd8ea39e34f2f2d432a961be2a6a';
	const TOKEN_NOT_INCORRECT = '5f6bbd8ea39e34f212d432a968be2abe';
	
	const PASSWORD_HASH = '$2y$10$VeTx5VTpb4AGoLRO6FfVxuNM5Xqbf7SgbC1LMvuMAi28RB9v3lPj.';
	
	const PASSWORD = 'Wwwqqq111';
	const PASSWORD_INCORRECT = 'Wwwqqq222';
	
	private static $users = [
		self::ID_ADMIN => [
			'id' => 1,
			'login' => 'admin',
			'status' => 1,
			'roles' => [
				'rAdministrator',
			],
		],
		self::ID_TESTER_2 => [
			'id' => 3,
			'login' => 'tester2',
			'status' => 1,
		],
	];
	
	public static function getEntityFormat() {
		return [
			'id' => isType::TYPE_INT,
			'login' => isType::TYPE_STRING,
			'status' => isType::TYPE_INT,
			'roles' => isType::TYPE_ARRAY,
		];
	}
	
	public static function getUser($id, $attribute = null) {
		$user = self::$users[$id];
		if(empty($attribute)) {
			return $user;
		} else {
			return $user[$attribute];
		}
	}
	
}
