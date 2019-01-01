<?php

namespace tests\functional\v1\services;

use yii2lab\test\helpers\DataHelper;
use yii2lab\test\Test\Unit;
use yii\web\NotFoundHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2module\account\domain\v2\entities\LoginEntity;
use tests\functional\v1\enums\LoginEnum;

class LoginTest extends Unit
{
	const PACKAGE = 'yii2bundle/yii2-account';
	
	public function testOneByLogin()
	{
		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->login->oneByLogin(LoginEnum::LOGIN_ADMIN);
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $entity);
	}
	
	public function testOne()
	{
		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->login->oneById(LoginEnum::ID_ADMIN);
		$this->tester->assertEntity(LoginEnum::getUser(LoginEnum::ID_ADMIN), $entity);
	}
	
	public function testOneWithRelation()
	{
		$query = Query::forge();
		//$query->with('assignments');
		$query->with('security');
		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->login->oneById(LoginEnum::ID_ADMIN, $query);
		
		$oo = [
			'id' => '381949',
			'login' => '77771111111',
			'status' => '1',
			'roles' => [
				'rAdministrator',
			],
			/*'assignments' => [
				'rAdministrator',
			],*/
			//'token' => LoginEnum::TOKEN_ADMIN,
			'email' => '',
			'created_at' => '2018-03-28 21:00:13',
		];
		
		$this->tester->assertEntity($oo, $entity);
	}
	
	public function testOneByLoginNotFound()
	{
		try {
			\App::$domain->account->login->oneByLogin(LoginEnum::LOGIN_NOT_EXISTS);
			$this->tester->assertBad();
		} catch(NotFoundHttpException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testOneByIdNotFound()
	{
		try {
			\App::$domain->account->login->oneById(LoginEnum::ID_NOT_EXISTS);
			$this->tester->assertBad();
		} catch(NotFoundHttpException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testAll()
	{
		/** @var BaseEntity[] $collection */
		$query = Query::forge();
		$collection = \App::$domain->account->login->all($query);
		
		$expect = DataHelper::loadForTest(self::PACKAGE, __METHOD__, $collection);
		$this->tester->assertCollection($expect, $collection, true);
	}
	
}
