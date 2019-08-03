<?php

use yii\helpers\ArrayHelper;
use yii2tool\test\helpers\TestHelper;

$config = [
	'servers' => [
		'db' => [
			'test' => [
				'driver' => 'sqlite',
				'dbname' => TestHelper::PACKAGE_TEST_DB_FILE,
			],
		],
		'filedb' => [
			'path' => '@yii2module/account/domain/v3/fixtures/data',
		],
	],
	'jwt' => [
		'profiles' => [
			'auth' => [
				'key' => 'W4PpvVwI82Rfl9fl2R9XeRqBI0VFBHP3',
				'lifetime' => \yii2rails\extension\enum\enums\TimeEnum::SECOND_PER_YEAR,
			],
		],
	],
];

$baseConfig = TestHelper::loadConfig('common/config/env-local.php');
return ArrayHelper::merge($baseConfig, $config);
