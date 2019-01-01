OAuth
===
`main-local.php`:

```php
return [
	'components' => [
		'authClientCollection' => [
			'class' => 'yii\authclient\Collection',
			'clients' => [
				'github' => [
					'class' => 'yii\authclient\clients\Github',
					'clientId' => '',
					'clientSecret' => '',
				],
			],
		],
	],
];
```

