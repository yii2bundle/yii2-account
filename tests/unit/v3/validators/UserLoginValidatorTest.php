<?php

namespace tests\unit\v3\validators;

use yii\base\Model;
use yii2bundle\geo\domain\validators\PhoneValidator;
use yii2tool\test\Test\Unit;
use tests\functional\v3\enums\LoginEnum;
use Yii;
use yii2bundle\account\domain\v3\entities\LoginEntity;
use yii2bundle\account\domain\v3\helpers\TestAuthHelper;
use yii2bundle\account\domain\v3\validators\PasswordValidator;
use yii2bundle\account\domain\v3\validators\UserLoginValidator;
use yubundle\common\enums\RegexpEnum;

class UserLoginValidatorTest extends Unit
{

    public function testSmall()
    {
        $model = new LoginForm;
        $model->login = 'ad';
        $model->validate();
        $this->tester->assertEquals([
            'login' => [
                'Login is invalid.',
        ],
        ], $model->errors);
    }

    public function testBadChar()
    {
        $model = new LoginForm;
        $model->login = 'админ';
        $model->validate();
        $this->tester->assertEquals([
            'login' => [
                'Login is invalid.',
            ],
        ], $model->errors);

        $model->login = 'admin 1';
        $model->validate();
        $this->tester->assertEquals([
            'login' => [
                'Login is invalid.',
            ],
        ], $model->errors);
    }

    public function testValid()
    {
        $model = new LoginForm;
        $model->login = 'admin';
        $model->validate();
        $this->tester->assertEquals([], $model->errors);
    }

    public function testValid2()
    {
        $model = new LoginForm;
        $model->login = 'admin-1';
        $model->validate();
        $this->tester->assertEquals([], $model->errors);
    }

    public function testValid3()
    {
        $model = new LoginForm;
        $model->login = '     admin   ';
        $model->validate();
        $this->tester->assertEquals([], $model->errors);
    }

}

class LoginForm extends Model {

    public $login;

    public function rules() {
        return [
            ['login', UserLoginValidator::class],
        ];
    }

}
