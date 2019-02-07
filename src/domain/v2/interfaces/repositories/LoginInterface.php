<?php

namespace yii2module\account\domain\v2\interfaces\repositories;

use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\repositories\CrudInterface;
use yii2module\account\domain\v2\entities\LoginEntity;

interface LoginInterface extends CrudInterface {
	
	//public function oneByPhone($phone, Query $query = null);
	
	/**
	 * @param string $login
	 *
	 * @return boolean
	 */
	public function isExistsByLogin($login);
	
	/**
	 * @param string     $login
	 *
	 * @param Query|null $query
	 *
	 * @return LoginEntity
	 * @throws NotFoundHttpException
	 */
	//public function oneByLogin($login, Query $query = null);
	
	/**
	 * @param string $token
	 * @param null|string $type
	 *
	 * @return LoginEntity
	 */
	public function oneByToken($token, $type = null);

}