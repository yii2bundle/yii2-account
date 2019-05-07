<?php

namespace yii2module\account\domain\v3\interfaces\repositories;

use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\repositories\CrudInterface;
use yii2module\account\domain\v3\entities\LoginEntity;

interface LoginInterface extends CrudInterface {

    /**
     * @param            $phone
     * @param Query|null $query
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
	public function oneByPhone(string $phone, Query $query = null);
	
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
	public function oneByLogin($login, Query $query = null);

    public function oneByEmail(string $email, Query $query = null) : LoginEntity;

    public function oneByVirtual(string $login, Query $query = null) : LoginEntity;

	/**
	 * @param string $token
	 * @param null|string $type
	 *
	 * @return LoginEntity
	 */
	public function oneByToken($token, Query $query = null, $type = null);

}