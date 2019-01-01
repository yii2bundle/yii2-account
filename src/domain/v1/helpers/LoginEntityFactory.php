<?php

namespace yii2module\account\domain\v1\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\domain\helpers\ServiceHelper;

class LoginEntityFactory {
	
	public static function forgeLoginEntity($user)
	{
		if(empty($user)) {
			return null;
		}
		$user = ArrayHelper::toArray($user);
		foreach(\App::$domain->account->login->relations as $propName => $serviceName) {
			list($domain, $service) = explode(DOT, $serviceName);
			$user[$domain][$service] = ServiceHelper::oneById($serviceName, $user['login']);
		}
		return self::forgeEntity($user);
	}
	
	protected static function forgeEntity($data) {
		if(empty($data)) {
			return [];
		}
		if(!ArrayHelper::isIndexed($data)) {
			return \App::$domain->account->factory->entity->create('login', $data);
		}
		$result = [];
		foreach($data as $item) {
			$result[] = self::forgeEntity($item);
		}
		return $result;
	}
	
}