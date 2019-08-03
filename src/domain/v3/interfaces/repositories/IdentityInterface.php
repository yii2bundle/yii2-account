<?php

namespace yii2bundle\account\domain\v3\interfaces\repositories;

use yii\web\NotFoundHttpException;
use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\repositories\CrudInterface;

/**
 * Interface IdentityInterface
 * 
 * @package yii2bundle\account\domain\v3\interfaces\repositories
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
 */
interface IdentityInterface extends CrudInterface {
	
	/**
	 * @param string     $login
	 *
	 * @param Query|null $query
	 *
	 * @return \yii\web\IdentityInterface
	 * @throws NotFoundHttpException
	 */
	public function oneByLogin($login, Query $query = null);
	
}
