<?php

namespace yii2module\account\domain\v3\interfaces\services;

use yii\web\IdentityInterface;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2module\account\domain\v3\entities\LoginEntity;
use yii2module\account\domain\v3\forms\LoginForm;

/**
 * Interface AuthInterface
 *
 * @package yii2module\account\domain\v3\interfaces\services
 *
 * @property \yii2module\account\domain\v3\interfaces\repositories\AuthInterface $repository
 * @property \yii2module\account\domain\v3\entities\LoginEntity $identity
 */
interface AuthInterface {

    public function oneSelf(Query $query = null);
	
	public function isGuest() : bool;

    public function authenticationFromApi(LoginForm $model) : LoginEntity;

    public function authenticationFromWeb(LoginForm $model) : LoginEntity;

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