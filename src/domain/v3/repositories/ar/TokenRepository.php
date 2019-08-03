<?php

namespace yii2bundle\account\domain\v3\repositories\ar;

use yii2rails\domain\data\Query;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2bundle\account\domain\v3\interfaces\repositories\TokenInterface;

/**
 * Class TokenRepository
 * 
 * @package yii2bundle\account\domain\v3\repositories\ar
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
 */
class TokenRepository extends BaseActiveArRepository implements TokenInterface {

	protected $schemaClass;
	protected $primaryKey = 'token';

	public function tableName() {
		return 'user_token';
	}
	
	/**
	 * @return null
	 */
	public function getPrimaryKey() {
		return 'token';
	}
	
	public function oneByToken($token) {
		$query = Query::forge();
		$query->where('token', $token);
		return $this->one($query);
	}
	
	public function allByUserId($userId) {
		$query = Query::forge();
		$query->where(['user_id' => $userId]);
		return $this->all($query);
	}
	
	public function allByIp($ip) {
		$query = Query::forge();
		$query->where(['ip' => $ip]);
		return $this->all($query);
	}
	
	public function deleteOneByToken($token) {
		$query = $this->prepareQuery();
		$query->where(['token' => $token]);
		$this->deleteOneByQuery($query);
	}
	
	public function deleteAllExpired() {
		$query = $this->prepareQuery();
		$query->where(['<', 'expire_at', TIMESTAMP]);
		$this->deleteAllByQuery($query);
	}
	
	private function deleteAllByQuery(Query $query) {
		$collection = $this->all($query);
		$this->deleteCollection($collection);
	}
	
	private function deleteOneByQuery(Query $query) {
		$entity = $this->one($query);
		$this->delete($entity);
	}
	
	private function deleteCollection($collection) {
		if(empty($collection)) {
			return;
		}
		foreach($collection as $entity) {
			$this->delete($entity);
		}
	}
	
	/*private function deleteAllExpiredByIp($ip) {
		$query = $this->prepareQuery();
		$query->where(['<', 'expire_at', TIMESTAMP]);
		$query->andWhere(['ip' => $ip]);
		$this->deleteAllByQuery($query);
	}
	
	private function deleteByIp($ip) {
		$query = $this->prepareQuery();
		$query->where(['ip' => $ip]);
		$this->deleteAllByQuery($query);
	}
	
	private function oneByIp($ip) {
		$query = Query::forge();
		$query->where(['ip' => $ip]);
		return $this->one($query);
	}*/
	
}
