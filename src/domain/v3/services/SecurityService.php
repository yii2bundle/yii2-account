<?php

namespace yii2bundle\account\domain\v3\services;

use Yii;
use yii2rails\domain\data\Query;
use yii2rails\domain\helpers\Helper;
use yii2rails\domain\services\base\BaseActiveService;
use yii2bundle\account\domain\v3\entities\SecurityEntity;
use yii2bundle\account\domain\v3\forms\ChangeEmailForm;
use yii2bundle\account\domain\v3\forms\ChangePasswordForm;
use yii2bundle\account\domain\v3\interfaces\services\SecurityInterface;
use yii2rails\domain\services\base\BaseService;

/**
 * Class SecurityService
 *
 * @package yii2bundle\account\domain\v3\services
 *
 * @property-read \yii2bundle\account\domain\v3\interfaces\repositories\SecurityInterface $repository
 */
class SecurityService extends BaseService implements SecurityInterface {
	
	public function oneByIdentityId(int $identityId, Query $query = null) : SecurityEntity {
		return $this->repository->oneByIdentityId($identityId, $query);
	}
	
	public function changeEmail(array $body) {
		$body = Helper::validateForm(ChangeEmailForm::class, $body);
		$this->repository->changeEmail($body['password'], $body['email']);
	}
	
	public function isValidPassword(int $identityId, string $password) : bool {
		$securityEntity = $this->repository->oneByIdentityId($identityId);
		return $securityEntity->isValidPassword($password);
	}
	
	public function make(int $identityId, string $password) : SecurityEntity {
		\App::$domain->account->identity->oneById($identityId);
		$securityEntity = new SecurityEntity;
		$securityEntity->identity_id = $identityId;
		$securityEntity->password = $password;
		return $this->repository->insert($securityEntity);
	}
	
	public function savePassword(string $login, string $password) {
		$loginEntity = \App::$domain->account->login->oneByAny($login);
		/** @var SecurityEntity $securityEntity */
		$securityEntity = $this->repository->oneByIdentityId($loginEntity->id);
		$securityEntity->password = $password;
		$this->repository->update($securityEntity);
	}
	
	public function changePassword(array $body) {
		$body = Helper::validateForm(ChangePasswordForm::class, $body);
		$login = \App::$domain->account->auth->identity->login;
		$this->savePassword($login, $body['new_password']);
	}

}
