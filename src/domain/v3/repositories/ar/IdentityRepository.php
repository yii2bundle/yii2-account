<?php

namespace yii2bundle\account\domain\v3\repositories\ar;

use yii2bundle\account\domain\v3\entities\LoginEntity;
use yii2bundle\account\domain\v3\repositories\traits\LoginTrait;
use yii2rails\domain\data\Query;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2bundle\account\domain\v3\interfaces\repositories\IdentityInterface;
use yii2rails\domain\repositories\BaseRepository;
use yii\helpers\ArrayHelper;

/**
 * Class IdentityRepository
 * 
 * @package yii2bundle\account\domain\v3\repositories\ar
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
 */
class IdentityRepository extends BaseActiveArRepository implements IdentityInterface {

	use LoginTrait;
	
	protected $schemaClass = true;
	
	protected function prepareQuery(Query $query = null) {
		$query = Query::forge($query);
		$with = $query->getParam('with');
		
		if($with && in_array('roles', $with)) {
			$query->removeParam('with');
			ArrayHelper::removeValue($with, 'roles');
			$with[] = 'assignments';
			$with = array_values($with);
			$query->with($with);
		}
		
		return $query;
	}
	
}
