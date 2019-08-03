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
use yubundle\common\enums\RegexpEnum;

class PasswordValidatorTest extends Unit
{

    public function testLowerAndNum()
    {
        $model = new PasswordForm;
        $model->password = 'wwwqqq111';
        $model->validate();
        $this->tester->assertEquals([
            'password' => [
                'Bad password',
            ],
        ], $model->errors);
    }

    public function testLower()
    {
        $model = new PasswordForm;
        $model->password = 'wwwqqqzzz';
        $model->validate();
        $this->tester->assertEquals([
            'password' => [
                'Bad password',
            ],
        ], $model->errors);
    }

    public function testCharOnly()
    {
        $model = new PasswordForm;
        $model->password = 'Wwwqqqzzz';
        $model->validate();
        $this->tester->assertEquals([
            'password' => [
                'Bad password',
            ],
        ], $model->errors);
    }

    public function testNotValidChar()
    {
        $model = new PasswordForm;
        $model->password = 'Www*qqqzzz';
        $model->validate();
        $this->tester->assertEquals([
            'password' => [
                'Bad password',
            ],
        ], $model->errors);

        $model->password = 'Www qqqzzz';
        $model->validate();
        $this->tester->assertEquals([
            'password' => [
                'Bad password',
            ],
        ], $model->errors);
    }

    public function testSmall()
    {
        $model = new PasswordForm;
        $model->password = 'Wq1';
        $model->validate();
        $this->tester->assertEquals([
            'password' => [
                'Password should contain at least 6 characters.',
            ],
        ], $model->errors);
    }

    public function testBig()
    {
        $model = new PasswordForm;
        $model->password = 'Wq11111111111111111';
        $model->validate();
        $this->tester->assertEquals([
            'password' => [
                'Password should contain at most 18 characters.',
            ],
        ], $model->errors);
    }

    public function testValid()
    {
        $model = new PasswordForm;
        $model->password = 'Wwwqqq111';
        $model->validate();
        $this->tester->assertEquals([], $model->errors);
    }

    public function testValid2()
    {
        $model = new PasswordForm;
        $model->password = 'wwwqqQ111';
        $model->validate();
        $this->tester->assertEquals([], $model->errors);
    }

    public function testValid3()
    {
        $model = new PasswordForm;
        $model->password = '     wwwqqQ111   ';
        $model->validate();
        $this->tester->assertEquals([], $model->errors);
    }
}

class PasswordForm extends Model {

    public $password;

    public function rules() {
        return [
            ['password', PasswordValidator::class],
        ];
    }

}
