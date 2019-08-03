<?php

namespace yii2bundle\account\module;

/**
 * user module definition class
 */
class BackendModule extends Module
{

    public $controllerMap = [
        'auth' => [
            'class' => 'yii2bundle\account\module\controllers\AuthController',
            'layout' => '@yii2lab/applicationTemplate/backend/views/layouts/singleForm.php',
        ],
    ];

}
