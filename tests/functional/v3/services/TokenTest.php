<?php

namespace tests\functional\v3\services;

use yii2tool\test\Test\Unit;
use Yii;
use yii\web\NotFoundHttpException;
use yii2bundle\account\domain\v3\exceptions\InvalidIpAddressException;
use yii2bundle\account\domain\v3\exceptions\NotFoundLoginException;

class TokenTest extends Unit
{
	
	const IP = '192.168.44.92';
	const USER_ID = 2;
	const USER_ID_2 = 1;
	const INVALID_TOKEN = 'invalid_token';
	const INVALID_IP = '111.111.111.111';
	
	/*protected function _before() {
		parent::_before();
		\App::$domain->account->token->deleteAll();
	}
	
	public function testForgeNotFoundLogin() {
		try {
			\App::$domain->account->token->forge(999999, self::IP);
			$this->tester->assertBad();
		} catch(NotFoundLoginException $e) {
			$this->tester->assertNice();
		}
	}
	
	public function testDoubleForge() {
		$token1 = \App::$domain->account->token->forge(self::USER_ID, self::IP);
		$token2 = \App::$domain->account->token->forge(self::USER_ID, self::IP);
		$this->tester->assertEquals($token1, $token2);
		
		$token3 = \App::$domain->account->token->forge(self::USER_ID, self::INVALID_IP);
		$this->tester->assertNotEquals($token1, $token3);
		
		$token4 = \App::$domain->account->token->forge(self::USER_ID_2, self::IP);
		$this->tester->assertNotEquals($token1, $token4);
	}
	
	public function testExpire() {
		$token = \App::$domain->account->token->forge(self::USER_ID, self::IP, 1);
		
		$this->tester->assertTrue($this->isValidateToken(self::USER_ID, $token, self::IP));
		$this->tester->assertTrue($this->isValidateToken(self::USER_ID, $token, self::IP));
		//sleep(2);
		//$this->tester->assertFalse($this->isValidateToken(self::USER_ID, $token, self::IP));
	}
	
	public function testException()
	{
		$token = \App::$domain->account->token->forge(self::USER_ID, self::IP);
		$this->tester->assertTrue($this->isValidateToken(self::USER_ID, $token, self::IP));
		
		try {
			\App::$domain->account->token->validate($token, self::INVALID_IP);
			$this->tester->assertBad();
		} catch(InvalidIpAddressException $e) {
			$this->tester->assertNice();
		}
		
		try {
			\App::$domain->account->token->validate(self::INVALID_TOKEN, self::IP);
			$this->tester->assertBad();
		} catch(NotFoundHttpException $e) {
			$this->tester->assertNice();
		}
		
	}
	
	public function testDeleteOneByToken()
	{
		$token = \App::$domain->account->token->forge(self::USER_ID, self::IP);
		\App::$domain->account->token->deleteOneByToken($token);
		$this->tester->assertFalse($this->isValidateToken(self::USER_ID, $token, self::IP));
	}
	
	private function isValidateToken($userId, $token, $ip) {
		$this->tester->assertNotEmpty($token);
		$this->tester->assertNotEmpty($ip);
		try {
			$tokenEntity = \App::$domain->account->token->validate($token, $ip);
			$this->tester->assertEquals($tokenEntity->user_id, $userId);
			return true;
		} catch(InvalidIpAddressException $e) {
		
		} catch(NotFoundHttpException $e) {
		
		}
		return false;
	}*/
}
