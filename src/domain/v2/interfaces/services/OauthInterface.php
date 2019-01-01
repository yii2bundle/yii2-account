<?php

namespace yii2module\account\domain\v2\interfaces\services;

use yii\authclient\BaseOAuth;
use yii2module\account\domain\v2\entities\LoginEntity;

/**
 * Interface OauthInterface
 * 
 * @package yii2module\account\domain\v2\interfaces\services
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 */
interface OauthInterface {
	
	public function isEnabled() : bool;
	public function oneById($id) : LoginEntity;
	public function authByClient(BaseOAuth $client);
	
}
