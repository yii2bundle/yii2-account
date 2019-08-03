<?php

use yii\helpers\ArrayHelper;
use yii2rails\domain\enums\Driver;
use yii2tool\test\helpers\TestHelper;

$config = [
	'lang' => 'yii2bundle\lang\domain\Domain',
	'rbac' => 'yii2bundle\rbac\domain\Domain',
	'jwt' => 'yii2rails\extension\jwt\Domain',
	'account' => [
		'class' => 'yii2bundle\account\domain\v3\Domain',
		'primaryDriver' => Driver::FILEDB,
	],
];

$baseConfig = TestHelper::loadConfig('common/config/domains.php');
return ArrayHelper::merge($baseConfig, $config);
