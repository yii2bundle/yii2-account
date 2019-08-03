Установка
===

Устанавливаем зависимость:

```
composer require yii2module/yii2-account
```

Объявляем API модуль:

```php
return [
	'modules' => [
		// ...
		'account' => [
			'class' => 'yii2bundle\account\api\Module',
		],
		// ...
	],
];
```

Объявляем frontend модуль:

```php
return [
	'modules' => [
		// ...
		'user' => [
			'class' => 'yii2bundle\account\module\Module',
		],
		// ...
	],
];
```

Объявляем backend модуль:

```php
return [
	'modules' => [
		// ...
		'user' => [
			'class' => 'yii2bundle\account\module\Module',
			'controllerMap' => [
				'auth' => [
					'class' => 'yii2bundle\account\module\controllers\AuthController',
					'layout' => '@yii2lab/misc/backend/views/layouts/singleForm.php',
				],
			],
		],
		// ...
	],
];
```

Объявляем домен:

```php
return [
	'components' => [
		// ...
		'account' => [
			'class' => 'yii2rails\domain\Domain',
			'path' => 'yii2bundle\account\domain',
			'repositories' => [
				'auth' => Driver::remote(),
				'login' => Driver::remote(),
				'registration' => Driver::remote(),
				'temp' => Driver::ACTIVE_RECORD,
				'restorePassword' => Driver::remote(),
				'security' => Driver::remote(),
				'test' => Driver::DISC,
				'balance' => Driver::remote(),
				'rbac' => Driver::MEMORY,
				'confirm' => Driver::ACTIVE_RECORD,
				'assignment' => Driver::remote(),
			],
			'services' => [
				'auth',
				'login' => [
					'relations' => [
						'profile' => 'profile.profile',
						'address' => 'profile.address',
					],
					'prefixList' => ['B', 'BS', 'R', 'QRS'],
					'defaultRole' => RoleEnum::UNKNOWN_USER,
					'defaultStatus' => 1,
				],
				'registration' => $remoteServiceDriver,
				'temp',
				'restorePassword' => $remoteServiceDriver,
				'security',
				'test',
				'balance',
				'rbac',
				'confirm',
				'assignment',
			],
		],
		// ...
	],
];
```
