<?php

namespace yii2bundle\account\domain\v3\interfaces\services;

use yii\web\IdentityInterface;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2bundle\account\domain\v3\entities\LoginEntity;
use yii2bundle\account\domain\v3\forms\LoginForm;

/**
 * Interface AuthInterface
 *
 * @package yii2bundle\account\domain\v3\interfaces\services
 *
 * @property \yii2bundle\account\domain\v3\interfaces\repositories\AuthInterface $repository
 * @property \yii2bundle\account\domain\v3\entities\LoginEntity $identity
 */
interface AuthInterface {

    public function authenticationFromApi(LoginForm $model) : LoginEntity;
    public function authenticationFromWeb(LoginForm $model) : LoginEntity;
	public function authenticationByToken(string $token, string $type = null) : LoginEntity;
	public function authentication(string $login, string $password, string $ip = null) : LoginEntity;
	
	/**
	 * @deprecated use from App::$domain->account->user
	 */
	public function oneSelf(Query $query = null);
	/**
	 * @deprecated use from App::$domain->account->user
	 */
	public function isGuest() : bool;
	/**
	 * @deprecated use from App::$domain->account->user
	 */
    public function login(IdentityInterface $loginEntity, bool $rememberMe = false);
	
	/**
	 * @deprecated use from App::$domain->account->user
     * @return LoginEntity
     */
	public function getIdentity();
	/**
	 * @deprecated use from App::$domain->account->user
	 */
	public function logout();
	/**
	 * @deprecated use from App::$domain->account->user
	 */
	public function denyAccess();
	/**
	 * @deprecated use from App::$domain->account->user
	 */
	public function breakSession();
	/**
	 * @deprecated use from App::$domain->account->user
	 */
	public function loginRequired();
	/**
	 * @deprecated use from App::$domain->account->user
	 */
	
	/**
	 * @param BaseEntity $entity
	 * @param string     $fieldName
	 *
	 * @return mixed
	 *
	 * @deprecated
	 */
	//public function checkOwnerId(BaseEntity $entity, $fieldName = 'user_id');
	
}