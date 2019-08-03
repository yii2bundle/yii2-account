<?php

namespace yii2bundle\account\domain\v3\helpers\test;

use Yii;
use yii2lab\notify\domain\helpers\test\NotifyTestHelper;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\entities\ResponseEntity;
use yii2tool\test\helpers\RestTestHelper;
use yii2tool\test\helpers\TestHelper;
use yii2rails\app\domain\enums\YiiEnvEnum;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2rails\extension\web\enums\HttpMethodEnum;

class RegistrationTestHelper
{

    const PASSWORD = 'Wwwqqq111';

    public static function getlastPhone() {
        self::generateNewPhone();
        $phone = CurrentPhoneTestHelper::get();
        $isExistsPhone = self::checkLoginExists($phone);
        while($isExistsPhone) {
            $phone = PhoneTestHelper::nextPhone();
            $isExistsPhone = self::checkLoginExists($phone);
        }
        return $phone;
    }

    public static function registration()
    {
        AuthTestHelper::logout();
        $phone = self::getlastPhone();
        CurrentPhoneTestHelper::set($phone);

        AuthTestHelper::authByAccountManager();
        //self::requestActivationCode();
        self::createAccount();
        AuthTestHelper::loadPrevAuth();
    }

    private static function generateNewPhone()
    {
        $phone = PhoneTestHelper::nextPhone();
        CurrentPhoneTestHelper::set($phone);
    }

    private static function checkLoginExists($phone) : bool {
        AuthTestHelper::authByAccountManager();
        $requestEntity = new RequestEntity;
        $requestEntity->uri = 'v1/identity';
        $requestEntity->method = HttpMethodEnum::GET;
        $requestEntity->data = [
            'phone' => $phone,
        ];
        $responseEntity = RestTestHelper::sendRequest($requestEntity);
        $collection = $responseEntity->data;
        AuthTestHelper::loadPrevAuth();
        return !empty($collection);
    }

    private static function requestActivationCodeRequest($phone) : ResponseEntity {
        $requestEntity = new RequestEntity;
        $requestEntity->uri = 'v1/registration/request-activation-code';
        $requestEntity->method = HttpMethodEnum::POST;
        $requestEntity->data = [
            'phone' => $phone,
        ];
        $responseEntity = RestTestHelper::sendRequest($requestEntity);
        return $responseEntity;
    }

    private static function requestActivationCode() {
        NotifyTestHelper::cleanSms();
        $phone = self::getlastPhone();

        CurrentPhoneTestHelper::set($phone);
        $requestEntity = self::requestActivationCodeRequest($phone);
    }

    private static function createAccount() {
        $phone = CurrentPhoneTestHelper::get();
        //$code = NotifyTestHelper::getActivationCodeByPhone($phone);
        $requestEntity = new RequestEntity;
        $requestEntity->uri = 'v1/registration/create-account';
        $requestEntity->method = HttpMethodEnum::POST;
        $requestEntity->data = [
            'phone' => $phone,
            //'activation_code' => $code,
            'password' => self::PASSWORD,
            'login' => 'test' . $phone,
            'first_name' => 'Name',
            'last_name' => 'Family',
            //'middle_name' => 'Middle',
            'birthday' => '2018-03-20',
        ];
        $responseEntity = RestTestHelper::sendRequest($requestEntity);
    }

}
