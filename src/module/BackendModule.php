<?php

namespace yii2module\account\module;

/**
 * user module definition class
 */
class BackendModule extends Module
{

    public $controllerMap = [
        'auth' => [
            'class' => 'yii2module\account\module\controllers\AuthController',
            'layout' => '@yii2lab/applicationTemplate/backend/views/layouts/singleForm.php',
        ],
    ];

}
