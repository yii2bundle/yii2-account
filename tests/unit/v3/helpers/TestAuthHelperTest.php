<?php

namespace tests\unit\v3\helpers;

use yii2tool\test\Test\Unit;
use tests\functional\v3\enums\LoginEnum;
use Yii;
use yii2bundle\account\domain\v3\entities\LoginEntity;
use yii2bundle\account\domain\v3\helpers\TestAuthHelper;

class TestAuthHelperTest extends Unit
{
	
	public function testAuthById()
	{
		TestAuthHelper::authById(LoginEnum::ID_ADMIN);
		/** @var LoginEntity $loginEntity */
		$loginEntity = Yii::$app->user->identity;
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $loginEntity);
	}

	public function testAuthByLogin()
	{
		TestAuthHelper::authByLogin(LoginEnum::LOGIN_ADMIN);
		/** @var LoginEntity $loginEntity */
		$loginEntity = Yii::$app->user->identity;
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $loginEntity);
	}

}
