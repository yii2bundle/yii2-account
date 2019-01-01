<?php

namespace yii2module\account\domain\v1\services;

use yii2lab\domain\services\ActiveBaseService;

class TestService extends ActiveBaseService {

	public function getOneByRole($role) {
		$user = $this->repository->getOneByRole($role);
		return $user;
	}

	public function oneByLogin($login) {
		$user = $this->repository->oneByLogin($login);
		return $user;
	}

}
