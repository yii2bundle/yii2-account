<?php

namespace tests\functional\v3\services;

use yii2lab\test\Test\Unit;
use Yii;
use yii2module\account\domain\v3\entities\LoginEntity;
use tests\functional\v3\enums\LoginEnum;

class SecurityTest extends Unit
{
	
	public function testOneById()
	{
		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->security->oneByLoginId(LoginEnum::ID_ADMIN);
		$this->tester->assertEntity([
			'identity_id' => LoginEnum::ID_ADMIN,
			'password_hash' => LoginEnum::PASSWORD_HASH,
		], $entity);
	}
	
}
