<?php

namespace tests\functional\v1\services;

use yii2lab\test\Test\Unit;
use Yii;
use yii2module\account\domain\v2\entities\LoginEntity;
use tests\functional\v1\enums\LoginEnum;

class SecurityTest extends Unit
{
	
	public function testOneById()
	{
		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->security->oneById(LoginEnum::ID_ADMIN);
		$this->tester->assertEntity([
			'id' => LoginEnum::ID_ADMIN,
			'email' => '',
			//'token' => LoginEnum::TOKEN_ADMIN,
			'password_hash' => LoginEnum::PASSWORD_HASH,
		], $entity);
	}
	
	public function testSecurityCheck()
	{
		$collection = \App::$domain->account->security->all();
		$this->tester->assertEquals([], $collection);
	}
	
}
