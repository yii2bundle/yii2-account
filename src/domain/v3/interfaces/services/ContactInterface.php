<?php

namespace yii2bundle\account\domain\v3\interfaces\services;

use yii2bundle\account\domain\v3\entities\ContactEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface ContactInterface
 * 
 * @package yii2bundle\account\domain\v3\interfaces\services
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
 * @property-read \yii2bundle\account\domain\v3\interfaces\repositories\ContactInterface $repository
 */
interface ContactInterface extends CrudInterface {
	
	public function oneByData($data, $type, Query $query = null) : ContactEntity;
	
	public function isExistsByData($data, $type) : bool;
	
}
