<?php

namespace yii2module\account\domain\v3\interfaces\services;

use yii2module\account\domain\v3\entities\SecurityEntity;
use yii2rails\domain\interfaces\services\CrudInterface;

interface SecurityInterface extends CrudInterface {
	
	public function make(int $identityId, string $password) : SecurityEntity;
	public function changeEmail(array $body);
	public function changePassword(array $body);
	public function isValidPassword(int $loginId, string $password) : bool;
	public function savePassword(string $login, string $password);
	
}