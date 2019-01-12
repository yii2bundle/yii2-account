<?php

namespace yii2module\account\domain\v2\services;

use yii2lab\domain\services\base\BaseActiveService;
use yii2module\account\domain\v2\interfaces\services\TestInterface;

/**
 * Class TestService
 *
 * @package yii2module\account\domain\v2\services
 * @property \yii2module\account\domain\v2\interfaces\repositories\TestInterface $repository
 */
class TestService extends BaseActiveService implements TestInterface {

	public function getOneByRole($role) {
		$user = $this->repository->getOneByRole($role);
		return $user;
	}

	public function oneByLogin($login) {
		$user = $this->repository->oneByLogin($login);
		return $user;
	}

}
