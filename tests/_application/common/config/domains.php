<?php

use yii\helpers\ArrayHelper;
use yii2rails\domain\enums\Driver;
use yii2lab\test\helpers\TestHelper;

$config = [
	'lang' => 'yii2module\lang\domain\Domain',
	'rbac' => 'yii2lab\rbac\domain\Domain',
	'jwt' => 'yii2rails\extension\jwt\Domain',
	'account' => [
		'class' => 'yii2module\account\domain\v3\Domain',
		'primaryDriver' => Driver::FILEDB,
	],
];

$baseConfig = TestHelper::loadConfig('common/config/domains.php');
return ArrayHelper::merge($baseConfig, $config);
