<?php

namespace yii2module\account\domain\v3\helpers\test;

use Yii;
use yii\web\UnauthorizedHttpException;
use yii2lab\test\helpers\RestTestHelper;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\extension\enum\enums\TimeEnum;
use App;
use yii\web\NotFoundHttpException;
use yii2lab\notify\domain\entities\SmsEntity;
use yii2lab\notify\domain\entities\TestEntity;
use yii2lab\notify\domain\enums\TypeEnum;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\entities\ResponseEntity;
use yii2lab\rest\domain\helpers\RestHelper;
use yii2rails\app\domain\helpers\Config;
use yii2rails\app\domain\helpers\Env;
use yii2rails\extension\web\enums\HttpMethodEnum;
use yii2rails\extension\yii\helpers\FileHelper;
use yii2module\account\domain\v3\entities\LoginEntity;

class AuthTestHelper
{

    private static $tokenCollection = [];
    private static $identity = null;
    private static $identityStack = [];

    public static function authByToken($token) {
        $identity = new LoginEntity;
        $identity->token = $token;
        AuthTestHelper::login($identity);
    }

    public static function authByLogin($login, $password = 'Wwwqqq111') {
        if(isset(self::$tokenCollection[$login])) {
            self::login(self::$tokenCollection[$login]);
            return;
        }
        $requestEntity = new RequestEntity;
        $requestEntity->method = HttpMethodEnum::POST;
        $requestEntity->uri = 'v1/auth';
        $requestEntity->data = [
            'login' => $login,
            'password' => $password,
        ];
        $responseEntity = RestTestHelper::sendRequest($requestEntity);
        if($responseEntity->status_code != 200) {
            throw new UnauthorizedHttpException;
        }

        $loginEntity = new LoginEntity($responseEntity->data);
        self::login($loginEntity);
        self::$tokenCollection[$login] = $loginEntity;
    }

    public static function getIdentity() {
        return self::$identity;
    }

    public static function login(LoginEntity $loginEntity) {
        self::saveCurrentAuth();
        self::$identity = $loginEntity;
    }

    public static function logout() {
        self::saveCurrentAuth();
        self::$identity = null;
    }

    public static function loadPrevAuth() {
        self::$identity = array_pop(self::$identityStack);
    }

    private static function saveCurrentAuth() {
        array_push(self::$identityStack, self::$identity);
    }

}
