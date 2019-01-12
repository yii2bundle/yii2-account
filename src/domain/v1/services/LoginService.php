<?php

namespace yii2module\account\domain\v1\services;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\domain\helpers\Helper;
use yii2lab\domain\services\base\BaseActiveService;
use yii2module\account\domain\v1\interfaces\services\LoginInterface;
use yii2module\account\domain\v1\forms\LoginForm;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class LoginService
 *
 * @package yii2module\account\domain\v1\services
 *
 * @property \yii2module\account\domain\v1\interfaces\repositories\LoginInterface $repository
 */
class LoginService extends BaseActiveService implements LoginInterface {

	public $relations = [];
	public $prefixList = [];
	public $defaultRole;
	public $defaultStatus;
	public $forbiddenStatusList;
	
	public function allById($id) {
	    return $this->repository->allById($id);
	}
	
	public function oneByLogin($login) {
		$user = $this->repository->oneByLogin($login);
		return $user;
	}
	
	public function oneByRole($role) {
		$user = $this->repository->oneByRole($role);
		return $user;
	}
	
	public function allByRole($roleName) {
		return $this->repository->allByRole($roleName);
	}
	
	public function create($data) {
		//$data['role'] = !empty($data['role']) ? $data['role'] : RoleEnum::UNKNOWN_USER;
		$data['email'] = !empty($data['email']) ? $data['email'] : 'api@example.com';
        Helper::validateForm(LoginForm::class, $data);
		try {
			$user = $this->repository->oneByLogin($data['login']);
			$error = new ErrorCollection();
			$error->add('login', 'account/registration', 'user_already_exists_and_activated');
			throw new UnprocessableEntityHttpException($error);
		} catch(NotFoundHttpException $e) {
			//$data['roles'] = $data['role'];
			$data['password_hash'] = Yii::$app->security->generatePasswordHash($data['password']);
			$entity = $this->domain->factory->entity->create($this->id, $data);
			$this->repository->insert($entity);
			if(!empty($entity->id)) {
				if (!empty($data['role'])){
					$role = ArrayHelper::toArray($data['role']);
					foreach ($role as $item){
						\App::$domain->rbac->assignment->assign($item, $entity->id);
					}
				} else {
					\App::$domain->rbac->assignment->assign($this->defaultRole, $entity->id);
				}
			}
			return $entity;
		}
	}
	
	public function isForbiddenByStatus($status) {
	    if(empty($this->forbiddenStatusList)) {
			return false;
		}
		return in_array($status, $this->forbiddenStatusList);
	}

}
