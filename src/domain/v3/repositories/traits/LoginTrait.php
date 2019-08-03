<?php

namespace yii2bundle\account\domain\v3\repositories\traits;

use yii2bundle\account\domain\v3\entities\LoginEntity;
use yii2rails\domain\data\Query;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2bundle\account\domain\v3\interfaces\repositories\IdentityInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Trait LoginTrait
 *
 * @package yii2bundle\account\domain\v3\repositories\traits
 * @property Alias $alias
 * @property ActiveRecordInterface $model
 * @property \yii2bundle\account\domain\v3\Domain $domain
 */
trait LoginTrait {
	
	public function tableName()
	{
		return 'user_identity';
	}
	
	public function uniqueFields() {
		return ['login'];
	}
	
	public function oneByLogin($login, Query $query = null) : LoginEntity {
		$query = Query::forge($query);
		$query->where(['login' => $login]);
		$loginEntity = $this->one($query);
		return $loginEntity;
	}

}