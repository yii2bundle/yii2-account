<?php

namespace yii2bundle\account\domain\v3\helpers;

use yii2rails\extension\common\enums\RegexpPatternEnum;
use yii2rails\extension\common\helpers\StringHelper;

class LoginTypeHelper  {

    public static function getLoginType(string $login) {
        if(LoginTypeHelper::isPhone($login)) {
            return 'phone';
        } elseif(LoginTypeHelper::isEmail($login)) {
            return 'email';
        } elseif(LoginTypeHelper::isToken($login)) {
            return 'token';
        } elseif(LoginTypeHelper::isLogin($login)) {
            return 'login';
        }
        return null;
    }

    private static function normalizeCommon($login) {
        $login = mb_strtolower($login);
        $login = trim($login);
        $login = StringHelper::removeAllSpace($login);
        return $login;
    }

    public static function normalize($login) {
        $loginType = LoginTypeHelper::getLoginType($login);
        if($loginType == 'phone') {
            $login = LoginTypeHelper::normalizePhone($login);
        }
        return $login;
    }

    public static function normalizePhone($login) {
        $login = self::normalizeCommon($login);
        $login = str_replace(['+', '-', '(', ')', ' '], '', $login);
        $login{0} = str_replace('8', '7', $login{0});
        return $login;
    }

    public static function normalizeEmail($login) {
        $login = self::normalizeCommon($login);
        return $login;
    }

    public static function normalizeToken($login) {
        $login = self::normalizeCommon($login);
        return $login;
    }

    public static function normalizeLogin($login) {
        $login = self::normalizeCommon($login);
        return $login;
    }

	public static function isPhone($login) {
        $login = self::normalizePhone($login);
		return preg_match('#^[0-9]{9,}$#i', $login);
	}

    public static function isEmail($login) {
        $login = self::normalizeEmail($login);
        return preg_match(RegexpPatternEnum::EMAIL_REQUIRED, $login);
    }
	
	public static function isToken($login) {
        $login = trim($login);
		return preg_match('/^([0-9a-z]+)\s+(\S+)$/i', $login);
	}
	
	public static function isLogin($login) {
        $login = self::normalizeLogin($login);
		return preg_match('/^[a-z]+[a-z0-9_-]{3,16}$/i', $login);
	}

}
