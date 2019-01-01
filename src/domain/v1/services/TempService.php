<?php

namespace yii2module\account\domain\v1\services;

use yii2lab\extension\enum\enums\TimeEnum;
use yii2module\account\domain\v1\entities\TempEntity;
use yii2lab\domain\services\BaseService;
use Yii;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;

class TempService extends BaseService {

    public $loginExpire = TimeEnum::SECOND_PER_MINUTE * 20;

	public function isActivated($login) {
		$user = $this->oneByLogin($login);
		return $user->ip == Yii::$app->request->getUserIP();
	}
	
	public function checkActivationCode($login, $code) {
		$user = $this->oneByLogin($login);
		if(empty($user) || $user->activation_code != $code) {
			return false;
		}
		return $user;
	}
	
	public function activate($login) {
		$user = $this->oneByLogin($login);
		$user->ip = Yii::$app->request->getUserIP();
		$this->repository->update($user);
		return true;
	}
	
	public function create($data) {
		$isExists = $this->repository->isExists($data['login']);
		if($isExists) {
			$error = new ErrorCollection();
			$error->add('login', 'account/registration', 'user_already_exists_but_not_activation');
			throw new UnprocessableEntityHttpException($error);
		}
		$entity = new TempEntity;
		$entity->login = $data['login'];
		$entity->email = $data['email'];
		$entity->activation_code = $data['activation_code'];
		return $this->repository->insert($entity);
	}

	public function delete($login) {
		$user = $this->oneByLogin($login);
		return $this->repository->delete($user);
	}
	
	private function isExpire($created_at) {
		$left = TIMESTAMP - $created_at;
		return $left > $this->loginExpire;
	}
	
	private function oneByLogin($login) {
		$user = $this->repository->oneByLogin($login);
		if(empty($user)) {
			return null;
			//throw new NotFoundHttpException(__METHOD__ . ': ' . __LINE__);
		}
		$created_at = strtotime($user->created_at);
		if($this->isExpire($created_at)) {
			$this->repository->delete($user);
			return null;
		}
		return $user;
	}
	
}
