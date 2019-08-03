<?php

namespace tests\unit\v3\helpers;

use yii2tool\test\Test\Unit;
use yii2bundle\account\domain\v3\helpers\LoginHelper;

class LoginHelperTest extends Unit
{
	
	public function testPregMatchLoginInternationalFormat()
	{
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('77758889900'));
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('+7 (775) (888)-(99)-(00)'));
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('+7 (775) (888)-(9900)'));
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('+77758889900'));
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('+7-775-888-99-00'));
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('+7 775 888 99 00'));
	}
	
	public function testPregMatchLoginKzFormat()
	{
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('87758889900'));
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('8 (775) (888)-(99)-(00)'));
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('8 (775) (888)-(9900)'));
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('87758889900'));
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('8-775-888-99-00'));
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('8 775 888 99 00'));
	}
	
	public function testPregMatchLoginWithPrefix()
	{
		$this->tester->assertEquals('R77758889900', LoginHelper::pregMatchLogin('R87758889900'));
		$this->tester->assertEquals('R77758889900', LoginHelper::pregMatchLogin('R8 (775) (888)-(99)-(00)'));
		$this->tester->assertEquals('R77758889900', LoginHelper::pregMatchLogin('R+7 (775) (888)-(99)-(00)'));
		$this->tester->assertEquals('BS77758889900', LoginHelper::pregMatchLogin('BS8 775 888 99 00'));
	}

	public function testPregMatchLoginNonStandardFormat()
	{
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('+7   775  888  99  00'));
		$this->tester->assertEquals('77758889900', LoginHelper::pregMatchLogin('+++++7------775----888----99-----00'));
	}
	
	public function testSplitLogin()
	{
		$this->tester->assertEquals([
			'prefix' => 'R',
            'country_code' => '7',
			'phone' => '7758889900',
		], LoginHelper::splitLogin('R77758889900'));
		$this->tester->assertEquals([
			'prefix' => 'R',
            'country_code' => '+7',
			'phone' => '7758889900',
		], LoginHelper::splitLogin('R+77758889900'));
		$this->tester->assertEquals([
			'prefix' => '',
			'country_code' => '7',
			'phone' => '7758889900',
		], LoginHelper::splitLogin('77758889900'));
//		$this->tester->assertEquals(LoginHelper::splitLogin('R8 (775) (888)-(99)-(00)'), [
//			'prefix' => 'R',
//            'country_code' => '8',
//			'phone' => '8 (775) (888)-(99)-(00)',
//		]);
		$this->tester->assertEquals([
			'prefix' => 'BS',
			'country_code' => '7',
			'phone' => '7758889900',
		], LoginHelper::splitLogin('BS77758889900'));
	}
	
	public function testValidateFormatSuccess()
	{
		$this->tester->assertEquals(true, LoginHelper::validate('+7   775  888  99  00'));
		$this->tester->assertEquals(true, LoginHelper::validate('R8 (775) (888)-(99)-(00)'));
		$this->tester->assertEquals(true, LoginHelper::validate('R+77758889900'));
		$this->tester->assertEquals(true, LoginHelper::validate('77758889900'));
		$this->tester->assertEquals(true, LoginHelper::validate('+++++7------775----888----99-----00'));
		$this->tester->assertEquals(true, LoginHelper::validate('+7 (775) (888)-(99)-(00)'));
	}
	
	public function testValidatePrefixSuccess()
	{
		$this->tester->assertEquals(true, LoginHelper::validate('R77758889900'));
		$this->tester->assertEquals(true, LoginHelper::validate('B77758889900'));
		$this->tester->assertEquals(true, LoginHelper::validate('BS77758889900'));
	}
	
	public function testValidateFormatFail()
	{
		$this->tester->assertEquals(false, LoginHelper::validate('+7 775'));
		$this->tester->assertEquals(false, LoginHelper::validate('R8 (775) (888)-(99)'));
		$this->tester->assertEquals(false, LoginHelper::validate('R+7775888'));
		// $this->tester->assertEquals(LoginHelper::validate('777588899008'), false);
		$this->tester->assertEquals(false, LoginHelper::validate('7775888990'));
		$this->tester->assertEquals(false, LoginHelper::validate('(775) (888)-(99)-(00)'));
	}
	
	public function testValidatePrefixFail()
	{
		$this->tester->assertEquals(false, LoginHelper::validate('FFFG77758889900'));
		$this->tester->assertEquals(false, LoginHelper::validate('FFBR77758889900'));
		$this->tester->assertEquals(false, LoginHelper::validate('FFSR77758889900'));
		$this->tester->assertEquals(false, LoginHelper::validate('FFRB77758889900'));
	}
	
	public function testParse()
	{
		$this->tester->assertEquals([
			'prefix' => 'BS',
			'country_code' => '7',
			'phone' => '7758889900',
		], LoginHelper::parse('BS77758889900'));
		$this->tester->assertEquals([
			'prefix' => 'R',
			'country_code' => '7',
			'phone' => '7758889900',
		], LoginHelper::parse('R8 (775) (888)-(99)-(00)'));
		$this->tester->assertEquals([
			'prefix' => '',
			'country_code' => '7',
			'phone' => '7758889900',
		], LoginHelper::parse('8 (775) (888)-(99)-(00)'));
	}
	
}
