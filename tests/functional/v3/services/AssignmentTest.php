<?php

namespace tests\functional\v3\services;

use yii2tool\test\Test\Unit;
use Yii;
use yii2rails\domain\data\Query;
use tests\functional\v3\enums\LoginEnum;
use yii2bundle\account\domain\v3\entities\AssignmentEntity;

class AssignmentTest extends Unit
{
	
	public function testAll()
	{
		/** @var AssignmentEntity[] $collection */
		$query = Query::forge();
		$query->where('user_id', LoginEnum::ID_ADMIN);
		$collection = \App::$domain->rbac->assignment->all($query);
		$this->tester->assertCollection([
			[
				'user_id' => LoginEnum::ID_ADMIN,
				'item_name' => 'rAdministrator',
			],
		], $collection, true);
		$this->tester->assertCount(1, $collection);
	}
	
	public function testAll2()
	{
		/** @var AssignmentEntity[] $collection */
		$query = Query::forge();
		$query->where('user_id', LoginEnum::ID_TESTER_1);
		$collection = \App::$domain->rbac->assignment->all($query);
		$this->tester->assertCollection([
			[
				'user_id' => LoginEnum::ID_TESTER_1,
				'item_name' => 'rTester',
			],
		], $collection, true);
		$this->tester->assertCount(1, $collection);
	}
	
	public function testAllAssignments()
	{
		/** @var AssignmentEntity[] $collection */
		$collection = \App::$domain->rbac->assignment->getAssignments(LoginEnum::ID_ADMIN);
		$this->tester->assertEquals([
			'rAdministrator' => new \yii\rbac\Assignment([
				'userId' => LoginEnum::ID_ADMIN,
				'roleName' => 'rAdministrator',
				'createdAt' => '1486774821',
			]),
		], $collection);
		$this->tester->assertCount(1, $collection);
	}
	
	public function testIsHasRole()
	{
		$isHas = \App::$domain->rbac->assignment->isHasRole(LoginEnum::ID_ADMIN, 'rAdministrator');
		$this->tester->assertTrue($isHas);
	}
	
	public function testIsHasRoleNegative()
	{
		$isHas = \App::$domain->rbac->assignment->isHasRole(LoginEnum::ID_TESTER_1, 'rAdministrator');
		$this->tester->assertFalse($isHas);
	}
	
	public function testAllUserIdsByRole()
	{
		$ids = \App::$domain->rbac->assignment->getUserIdsByRole('rUnknownUser');
		$this->tester->assertEquals([], $ids);
		
		$ids = \App::$domain->rbac->assignment->getUserIdsByRole('rAdministrator');
		$this->tester->assertEquals([
			0 => 1,
		], $ids);
	}
	
}
