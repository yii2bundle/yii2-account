<?php

namespace yii2bundle\account\domain\v3\interfaces\services;

use yii\web\IdentityInterface;
use yii2rails\domain\data\Query;
use yii2bundle\account\domain\v3\entities\LoginEntity;

/**
 * Interface UserInterface
 * 
 * @package yii2bundle\account\domain\v3\interfaces\services
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
 * @property-read \yii2bundle\account\domain\v3\interfaces\repositories\UserInterface $repository
 * @property \yii2bundle\account\domain\v3\entities\LoginEntity $identity
 */
interface UserInterface {
	
	public function oneSelf(Query $query = null) : LoginEntity;
	public function isGuest() : bool;
	public function login(IdentityInterface $loginEntity, bool $rememberMe = false);
	public function getIdentity() : LoginEntity;
	public function logout();
	public function denyAccess();
	public function breakSession();
	public function loginRequired();
	
}
