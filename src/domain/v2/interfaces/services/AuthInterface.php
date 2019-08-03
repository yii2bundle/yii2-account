<?php

namespace yii2bundle\account\domain\v2\interfaces\services;

use yii\web\IdentityInterface;
use yii2rails\domain\BaseEntity;
use yii2bundle\account\domain\v2\entities\LoginEntity;

/**
 * Interface AuthInterface
 *
 * @package yii2bundle\account\domain\v2\interfaces\services
 *
 * @property \yii2bundle\account\domain\v2\interfaces\repositories\AuthInterface $repository
 * @property \yii2bundle\account\domain\v2\entities\LoginEntity $identity
 */
interface AuthInterface {
	
	/**
	 * @param      $login
	 * @param      $password
	 *
	 * @param null $ip
	 *
	 * @return LoginEntity
	 */
	public function authentication($login, $password, $ip = null);

    /**
     * @return LoginEntity
     */
	//public function authenticationFromWeb($login, $password, $rememberMe = false);

    /**
     * @return LoginEntity
     */
	public function authenticationByToken($token, $type = null);

    public function login(IdentityInterface $loginEntity, $rememberMe = false);

    /**
     * @return LoginEntity
     */
	public function getIdentity();
	public function logout();
	public function denyAccess();
	public function breakSession();
	public function loginRequired();
	
	/**
	 * @param BaseEntity $entity
	 * @param string     $fieldName
	 *
	 * @return mixed
	 *
	 * @deprecated
	 */
	public function checkOwnerId(BaseEntity $entity, $fieldName = 'user_id');
	
}