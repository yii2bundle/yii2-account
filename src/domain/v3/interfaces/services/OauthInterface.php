<?php

namespace yii2module\account\domain\v3\interfaces\services;

use yii\authclient\BaseOAuth;
use yii\web\IdentityInterface;
use yii2module\account\domain\v3\entities\LoginEntity;

/**
 * Interface OauthInterface
 * 
 * @package yii2module\account\domain\v3\interfaces\services
 * 
 * @property-read \yii2module\account\domain\v3\Domain $domain
 */
interface OauthInterface {
	
	public function isEnabled() : bool;
	public function oneById($id) : IdentityInterface;
	public function authByClient(BaseOAuth $client);
	
}
