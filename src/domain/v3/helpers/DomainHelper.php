<?php

namespace yii2bundle\account\domain\v3\helpers;

use yii\helpers\ArrayHelper;
use yii2bundle\account\domain\v3\filters\token\DefaultFilter;
use yii2rails\domain\enums\Driver;

class DomainHelper  {

    public static function config($config = []) {
        $baseConfig = [
            'class' => 'yii2bundle\account\domain\v3\Domain',
            'services' => [
                'auth' => [
                    'tokenAuthMethods' => [
                        'jwt' => DefaultFilter::class,
                    ],
                ],
                'login' => [
                    'forbiddenStatusList' => null,
                ],
            ],
        ];
        return ArrayHelper::merge($baseConfig, $config);
    }

}
