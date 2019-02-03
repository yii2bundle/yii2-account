<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

use yii\web\NotFoundHttpException;
use yii2rails\domain\interfaces\repositories\CrudInterface;
use yii2module\account\domain\v2\entities\LoginEntity;

interface LoginInterface extends CrudInterface {
	
	/**
	 * @param string $login
	 *
	 * @return boolean
	 */
	public function isExistsByLogin($login);
	
	/**
	 * @param string $login
	 *
	 * @return LoginEntity
	 *
	 * @throws NotFoundHttpException
	 */
	public function oneByLogin($login);
	
	/**
	 * @param string $token
	 * @param null|string $type
	 *
	 * @return LoginEntity
	 */
	public function oneByToken($token, $type = null);

}