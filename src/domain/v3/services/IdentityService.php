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

    protected function prepareQuery(Query $query = null)
    {
        $query = Query::forge($query);
        $phone = $query->getWhere('phone');
        if($phone) {
            $query->removeWhere('phone');
            $personIds = [];
            try {
                $personEntity = \App::$domain->user->person->oneByPhone($phone);
                $personIds[] = $personEntity->id;
                $query->andWhere(['person_id' => $personIds]);
            } catch (NotFoundHttpException $e) {
                $query->andWhere(['person_id' => 0]);
            }
        }
        return $query;
    }

}
