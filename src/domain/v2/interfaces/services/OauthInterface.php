<?php

namespace yii2bundle\account\domain\v2\interfaces\services;

use yii\authclient\BaseOAuth;
use yii\web\IdentityInterface;
use yii2bundle\account\domain\v2\entities\LoginEntity;

/**
 * Interface OauthInterface
 * 
 * @package yii2bundle\account\domain\v2\interfaces\services
 * 
 * @property-read \yii2bundle\account\domain\v2\Domain $domain
 */
interface OauthInterface {
	
	public function isEnabled() : bool;
	public function oneById($id) : IdentityInterface;
	public function authByClient(BaseOAuth $client);
	
}
