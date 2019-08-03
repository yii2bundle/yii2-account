<?php

namespace yii2bundle\account\domain\v3\interfaces\services;

use yii2bundle\account\domain\v3\entities\SecurityEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\services\CrudInterface;

interface SecurityInterface {
	
	public function make(int $identityId, string $password) : SecurityEntity;
	public function oneByIdentityId(int $identityId, Query $query = null) : SecurityEntity;
	public function changeEmail(array $body);
	public function changePassword(array $body);
	public function isValidPassword(int $identityId, string $password) : bool;
	public function savePassword(string $login, string $password);
	
}