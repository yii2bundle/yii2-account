<?php


use yii2module\account\domain\v1\helpers\UserFixture;
//Wwwqqq111

$passwordHash = '$2y$13$Xt.Zo0sIeIPi6CvraoUB1eihsZO3pFXx4rcYGWL9jGRr0YybqCqdK';

return UserFixture::generateAll([
	[
		'id' => 381949,
		'login' => '77771111111',
		'username' => 'Admin',
		'role' => 'rAdministrator',
		'password_hash' => $passwordHash,
		'balance' => [
			'blocked' => 136809,
			'active' => 23795,
		],
	],
	[
		'id' => 381069,
		'login' => '77783177384',
		'username' => 'User',
		'role' => 'rUnknownUser',
		'password_hash' => $passwordHash,
		'balance' => [
			'blocked' => 32678,
			'active' => 5667,
		],
	],
	[
		'id' => 381070,
		'login' => '77751112233',
		'username' => 'User',
		'role' => 'rUnknownUser',
		'password_hash' => $passwordHash,
		'balance' => [
			'blocked' => 3842,
			'active' => 889,
		],
	],
	[
		'id' => 375664,
		'login' => '77127113312',
		'username' => 'User',
		'role' => 'rUnknownUser',
		'password_hash' => $passwordHash,
		'balance' => [
			'blocked' => 5473,
			'active' => 19,
		],
	],
	[
		'id' => 381072,
		'login' => '77009204345',
		'username' => 'Finance specialist',
		'role' => 'rFinanceSpecialist',
		'password_hash' => $passwordHash,
		'balance' => [
			'blocked' => 456677,
			'active' => 5498,
		],
	],
	[
		'id' => 381073,
		'login' => '79295829957',
		'username' => 'user 2',
		'role' => 'rUnknownUser',
		'password_hash' => $passwordHash,
		'balance' => [
			'blocked' => 36894,
			'active' => 6597,
		],
	],
	[
		'id' => 381074,
		'login' => 'R77026142577',
		'username' => 'user 3',
		'role' => 'rUnknownUser,rResmiUnknownUser',
		'password_hash' => $passwordHash,
		'balance' => [
			'blocked' => 96735,
			'active' => 6545,
		],
	],
	[
		'id' => 381075,
		'login' => '70000000022',
		'username' => 'user 4',
		'role' => 'rUnknownUser',
		'password_hash' => $passwordHash,
		'balance' => [
			'blocked' => 9787,
			'active' => 76576,
		],
	],
	[
		'id' => 381076,
		'login' => '77471105109',
		'username' => 'user 5',
		'role' => 'rAdministrator',
		'password_hash' => $passwordHash,
		'balance' => [
			'blocked' => 528932,
			'active' => 121447,
		],
	],
]);
