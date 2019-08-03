<?php

namespace tests\functional\v3\services;

use yii2tool\test\helpers\DataHelper;
use yii2tool\test\Test\Unit;
use yii\web\NotFoundHttpException;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2bundle\account\domain\v3\entities\LoginEntity;
use tests\functional\v3\enums\LoginEnum;

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
		$query->with('contacts');
		/** @var LoginEntity $entity */
		$entity = \App::$domain->account->login->oneById(LoginEnum::ID_ADMIN, $query);
		
		$oo = [
			'id' => '1',
			'login' => 'admin',
			'status' => '1',
			'roles' => [
				'rAdministrator',
			],
			'token' => null,
			//'created_at' => '2018-03-28 21:00:13',
			'contacts' => [
				'phone' => '77771111111',
				'email' => 'admin@example.com',
			],
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
