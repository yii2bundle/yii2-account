<?php

namespace yii2module\account\domain\v3\services;

use Yii;
use yii2rails\domain\data\Query;
use yii2rails\domain\helpers\Helper;
use yii2rails\domain\services\base\BaseActiveService;
use yii2module\account\domain\v3\entities\SecurityEntity;
use yii2module\account\domain\v3\forms\ChangeEmailForm;
use yii2module\account\domain\v3\forms\ChangePasswordForm;
use yii2module\account\domain\v3\interfaces\services\SecurityInterface;

/**
 * Class SecurityService
 *
 * @package yii2module\account\domain\v3\services
 *
 * @property-read \yii2module\account\domain\v3\interfaces\repositories\SecurityInterface $repository
 */
class SecurityService extends BaseActiveService implements SecurityInterface {
	
	public function make($identityId, $password) {
		$securityEntity = new SecurityEntity;
		$securityEntity->login_id = $identityId;
		$securityEntity->password = $password;
		//$securityEntity->password_hash = Yii::$app->security->generatePasswordHash($password);
		$this->repository->insert($securityEntity);
	}
	
	/**
	 * for security reasons, turn off the list selection
	 * @param Query|null $query
	 *
	 * @return array|mixed|null
	 */
	public function all(Query $query = null) {
		return [];
	}
	
	public function changeEmail($body) {
		$body = Helper::validateForm(ChangeEmailForm::class, $body);
		$this->repository->changeEmail($body['password'], $body['email']);
	}
	
	public function isValidPassword($loginId, $password) {
		$securityEntity = $this->repository->oneByLoginId($loginId);
		return $securityEntity->isValidPassword($password);
	}
	
	public function changePassword($body) {
		$body = Helper::validateForm(ChangePasswordForm::class, $body);
		$identityId = \App::$domain->account->auth->identity->id;
		/** @var SecurityEntity $securityEntity */
		$securityEntity = $this->repository->oneByLoginId($identityId);
		$securityEntity->password = $body['new_password'];
		$this->repository->update($securityEntity);
	}

	/*public function create($data) {
		$securityEntity = new SecurityEntity;
		$securityEntity->load([
			'id' => $data['id'],
			'email' => $data['email'],
			'password_hash' => Yii::$app->security->generatePasswordHash($data['password']),
			'token' => $this->repository->generateUniqueToken(),
		]);
		$this->repository->insert($securityEntity);
	}*/
	
}
