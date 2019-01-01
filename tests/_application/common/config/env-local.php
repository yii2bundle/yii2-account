<?php

use yii\helpers\ArrayHelper;
use yii2lab\test\helpers\TestHelper;

$config = [
	'servers' => [
		'db' => [
			'test' => [
				'driver' => 'sqlite',
				'dbname' => TestHelper::PACKAGE_TEST_DB_FILE,
			],
		],
		'filedb' => [
			'path' => '@yii2module/account/domain/v2/fixtures/data',
		],
	],
];

$baseConfig = TestHelper::loadConfig('common/config/env-local.php');
return ArrayHelper::merge($baseConfig, $config);
