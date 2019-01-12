<?php

namespace yii2module\account\domain\v2\repositories\disc;

use yii2lab\domain\BaseEntity;
use yii2lab\extension\arrayTools\repositories\base\BaseActiveDiscRepository;
use yii2module\account\domain\v2\interfaces\repositories\LoginInterface;
use yii2lab\domain\data\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class LoginRepository extends BaseActiveDiscRepository implements LoginInterface {
	
	public $table = 'user';
	protected $schemaClass = true;
	
	public function fieldAlias() {
		return [
			'token' => 'auth_key',
		];
	}

	public function oneById($id, Query $query = null) {
		/** @var Query $query */
		$query = Query::forge($query);
		$query->removeParam('where');
		try {
			$q = clone $query;
			$q->where('id', $id);
			$one = $this->one($q);
		} catch(NotFoundHttpException $e) {
			$q = clone $query;
			$q->where('login', $id);
			$one = $this->one($q);
		}
		return $one;
	}

	public function isExistsByLogin($login) {
		return $this->isExists(['login' => $login]);
	}
	
	public function oneByLogin($login) {
		$query = Query::forge();
		$query->where('login', $login);
		return $this->one($query);
	}

	public function oneByToken($token, $type = null) {
		$query = Query::forge();
		$query->where('token', $token);
		return $this->one($query);
	}
	
	public function forgeEntity($user, $class = null)
	{
		if(empty($user)) {
			return null;
		}
		if(ArrayHelper::isIndexed($user)) {
			$collection = [];
			foreach($user as $item) {
				$collection[] = $this->forgeEntity($item, $class);
			}
			return $collection;
		}
		$user = ArrayHelper::toArray($user);
		$user['roles'] = ['rAdministrator']; //explode(',', $user['role']);
		$user = $this->alias->decode($user);
		return parent::forgeEntity($user);
	}
	
	public function insert(BaseEntity $entity) {
		// TODO: Implement insert() method.
	}

}