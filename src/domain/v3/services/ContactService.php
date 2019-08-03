<?php

namespace yii2bundle\account\domain\v3\services;

use yii\web\NotFoundHttpException;
use yii2bundle\account\domain\v3\entities\ContactEntity;
use yii2bundle\account\domain\v3\interfaces\services\ContactInterface;
use yii2rails\domain\data\Query;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class ContactService
 * 
 * @package yii2bundle\account\domain\v3\services
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
 * @property-read \yii2bundle\account\domain\v3\interfaces\repositories\ContactInterface $repository
 */
class ContactService extends BaseActiveService implements ContactInterface {

	public function oneByData($data, $type, Query $query = null) : ContactEntity {
		$query = Query::forge($query);
		$query->andWhere(['data' => $data, 'type' => $type]);
		return $this->one($query);
	}
	
	public function isExistsByData($data, $type) : bool {
		try {
			$this->oneByData($data, $type);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
}
