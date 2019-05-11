<?php

namespace yii2module\account\domain\v3\services;

use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2module\account\domain\v3\interfaces\services\IdentityInterface;
use yii2rails\domain\services\base\BaseActiveService;

/**
 * Class IdentityService
 * 
 * @package yii2module\account\domain\v3\services
 * 
 * @property-read \yii2module\account\domain\v3\Domain $domain
 * @property-read \yii2module\account\domain\v3\interfaces\repositories\IdentityInterface $repository
 */
class IdentityService extends BaseActiveService implements IdentityInterface {
	
	private $identity = null;
	
    protected function prepareQuery(Query $query = null)
    {
        $query = Query::forge($query);
        $phone = $query->getWhere('phone');
        if($phone) {
            $query->removeWhere('phone');
            try {
	            $contactEntity = \App::$domain->account->contact->oneByData($phone, 'phone');
	            $query->andWhere(['id' => $contactEntity->identity_id]);
            } catch(NotFoundHttpException $e) {
	            $query->andWhere(['id' => null]);
            }
        }
        return $query;
    }

}
