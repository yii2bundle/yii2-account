<?php

namespace yii2module\account\domain\v1\repositories\ar;

use Yii;
use yii\rbac\Assignment;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii2lab\domain\BaseEntity;
use yii2module\account\domain\v1\entities\LoginEntity;
use yii2module\account\domain\v1\helpers\LoginEntityFactory;
use yii2lab\domain\repositories\ActiveArRepository;
use yii\helpers\ArrayHelper;
use yii2module\account\domain\v1\interfaces\repositories\LoginInterface;
use yii2module\account\domain\v1\models\User;

class LoginRepository extends ActiveArRepository implements LoginInterface {

	protected $modelClass = 'yii2module\account\domain\v1\models\User';
	
	public function uniqueFields() {
		return ['login'];
	}
	
	public function isExistsByLogin($login) {
		return $this->isExists(['login' => $login]);
	}
	
	public function oneByRole($role) {
		$collection = \App::$domain->rbac->assignment->allByRole($role);
		if(count($collection) < 1) {
			return null;
		}
		return $this->forgeEntity($collection[0]);
	}
	
	public function allByRole($role) {
		$collection = \App::$domain->rbac->assignment->allByRole($role);
		$ids = ArrayHelper::getColumn($collection, 'user_id');
		return $this->allById($ids);
	}
	
	public function allById($id) {
		$models = $this->allModelsByCondition(['id' => $id]);
		return $this->forgeEntity($models);
	}
	
	public function oneByLogin($login) {
		$model = $this->oneModelByCondition(['login' => $login]);
		return $this->forgeEntity($model);
	}
	
	public function oneByToken($token, $type = null) {
		$model = $this->oneModelByCondition(['token' => $token]);
		return $this->forgeEntity($model);
	}
	
	public function fieldAlias() {
		return [
			'name' => 'username',
			'token' => 'auth_key',
		];
	}
	
	public function insert(BaseEntity $entity) {
		$this->findUnique($entity);
		/** @var LoginEntity $entityClone */
		$entityClone = clone $entity;
		$entityClone->showToken();
		/** @var IdentityInterface $model */
		$model = Yii::createObject(get_class($this->model));
		$model->id = $this->lastId() + 1;
		$model->login = $entityClone->login;
		$model->email = $entityClone->email;
		$model->password_hash = $entityClone->password_hash;
		$model->auth_key = $this->generateUniqueToken();
		$model->status = $entityClone->status !== null ? $entityClone->status : $this->domain->login->defaultStatus;
		$this->saveModel($model);
		$entity->id = $model->id;
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
		$user = ArrayHelper::toArray($user);
		$user['roles'] = \App::$domain->rbac->assignment->allRoleNamesByUserId($user['id']);
		$user = $this->alias->decode($user);
		if(empty($user['token'])) {
			$user['token'] = $this->generateToken($user['id']);
		}
		return LoginEntityFactory::forgeLoginEntity($user);
	}

	private function generateToken($userId) {
		/** @var User $model */
		$model = $this->model->findOne(['id' => $userId]);
		$model->auth_key = $this->generateUniqueToken();
		$model->save();
		return $model->auth_key;
	}
	
	private function generateUniqueToken() {
		do {
			$auth_key = Yii::$app->security->generateRandomString(64);
			$isExists = true;
			try {
				$this->oneByToken($auth_key);
			} catch(NotFoundHttpException $e) {
				$isExists = false;
			}
		} while($isExists);
		return $auth_key;
	}
}