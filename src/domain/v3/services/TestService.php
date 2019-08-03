<?php

namespace yii2bundle\account\domain\v3\services;

use yii2rails\domain\services\base\BaseActiveService;
use yii2bundle\account\domain\v3\interfaces\services\TestInterface;

/**
 * Class TestService
 *
 * @package yii2bundle\account\domain\v3\services
 * @property \yii2bundle\account\domain\v3\interfaces\repositories\TestInterface $repository
 */
class TestService extends BaseActiveService implements TestInterface {

	public function oneByLogin($login) {
		$user = $this->repository->oneByLogin($login);
		return $user;
	}

}
