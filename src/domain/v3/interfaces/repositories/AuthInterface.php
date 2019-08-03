<?php

namespace yii2bundle\account\domain\v3\interfaces\repositories;

use yii2bundle\account\domain\v3\entities\LoginEntity;

interface AuthInterface {
	
	/**
	 * @param string $login
	 * @param string $password
	 * @param null   $ip
	 *
	 * @return LoginEntity
	 */
	public function authentication($login, $password, $ip = null);

    //public function authenticationByToken($token/*, $type = null, $ip = null*/);
}