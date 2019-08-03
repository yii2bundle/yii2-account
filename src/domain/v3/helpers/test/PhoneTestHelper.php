<?php

namespace yii2bundle\account\domain\v3\helpers\test;

use Yii;
use yii2lab\notify\domain\helpers\test\NotifyTestHelper;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\entities\ResponseEntity;
use yii2lab\test\helpers\RestTestHelper;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2rails\extension\web\enums\HttpMethodEnum;

class PhoneTestHelper
{

    const CACHE_PHONE_KEY = 'rest-test-registration-phone-num13';
    const PHONE_CODE = '7799';
    const PHONE_DEFAULT_NUM = 1000000;
    const PASSWORD = 'Wwwqqq111';

    public static function nextPhone() {
        self::increment();
        return self::lastPhone();
    }

    public static function lastPhone() {
        $phoneNum = self::oneNum();
        return self::forgePhone($phoneNum);
    }

    private static function increment() {
        $key = self::key();
        $phoneNum = self::oneNum();
        Yii::$app->cache->set($key, $phoneNum + 1, TimeEnum::SECOND_PER_YEAR);
    }

    private static function oneNum() {
        $key = self::key();
        $closure = function () {
            return self::PHONE_DEFAULT_NUM;
        };
        $num = Yii::$app->cache->getOrSet($key, $closure, TimeEnum::SECOND_PER_YEAR);
        return $num;
    }

    private static function forgePhone($num) {
        $phone = self::PHONE_CODE . $num;
        return $phone;
    }

    private static function key() {
        return [self::CACHE_PHONE_KEY, 'url' => RestTestHelper::getBaseUrl()];
    }
}
