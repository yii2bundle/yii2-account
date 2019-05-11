<?php

namespace yii2module\account\domain\v2\repositories\traits;

use Codeception\Module\Cli;
use Yii;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii2rails\domain\Alias;
use yii2rails\domain\BaseEntity;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\entities\SecurityEntity;
use yii2module\account\domain\v2\entities\TokenEntity;
use yii2module\cleaner\domain\helpers\ClearHelper;

/**
 * Trait LoginTrait
 *
 * @package yii2module\account\domain\v2\repositories\traits
 * @property Alias $alias
 * @property ActiveRecordInterface $model
 * @property \yii2module\account\domain\v2\Domain $domain
 */
trait LoginTrait {
	
	public function tableName() {
		return 'user';
	}
	
	public function uniqueFields() {
		return ['login'];
	}
	
	public function isExistsByLogin($login) {
		return $this->isExists(['login' => $login]);
	}
	
	public function oneByLogin($login) {
		$model = $this->oneModelByCondition(['login' => $login]);
		return $this->forgeEntity($model);
	}
	
	public function oneByToken($token, $type = null) {
		/** @var TokenEntity $tokenEntity */
		$ip = ClientHelper::ip();
		$tokenEntity = \App::$domain->account->token->validate($token, $ip);
		return $this->oneById($tokenEntity->user_id);
	}
	
	public function insert(BaseEntity $loginEntity) {
		/** @var LoginEntity $loginEntity */
		$this->findUnique($loginEntity);
		/** @var IdentityInterface|ActiveRecord $model */
		$model = Yii::createObject(get_class($this->model));
		$model->id = $this->lastId() + 1;
		$model->login = $loginEntity->login;
		$model->status = $loginEntity->status !== null ? $loginEntity->status : \App::$domain->account->login->defaultStatus;
		$model->created_at = $loginEntity->created_at;
		$this->saveModel($model);
		$loginEntity->id = $model->id;
	}
	
	private function lastId() {
		$model = $this->model->find()->orderBy(['id' => SORT_DESC])->one();
		return $model->id;
	}
	
	public function forgeEntity($user, $class = null)
	{
		if(empty($user)) {
			return null;
		}
		if(is_array($user) && ArrayHelper::isIndexed($user)) {
			$collection = [];
			foreach($user as $item) {
				$collection[] = $this->forgeEntity($item);
			}
			return $collection;
		}
		$user['roles'] = \App::$domain->rbac->assignment->allRoleNamesByUserId($user['id']);
		$user = $this->alias->decode($user);
		return parent::forgeEntity($user);
	}

}