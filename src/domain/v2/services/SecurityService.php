<?php

namespace yii2module\account\domain\v2\services;

use Yii;
use yii2rails\domain\data\Query;
use yii2rails\domain\helpers\Helper;
use yii2rails\domain\services\base\BaseActiveService;
use yii2module\account\domain\v2\entities\SecurityEntity;
use yii2module\account\domain\v2\forms\ChangeEmailForm;
use yii2module\account\domain\v2\forms\ChangePasswordForm;
use yii2module\account\domain\v2\interfaces\services\SecurityInterface;

/**
 * Class SecurityService
 *
 * @package yii2module\account\domain\v2\services
 *
 * @property-read \yii2module\account\domain\v2\interfaces\repositories\SecurityInterface $repository
 */
class SecurityService extends BaseActiveService implements SecurityInterface {
	
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
	
	public function changePassword($body) {
		$body = Helper::validateForm(ChangePasswordForm::class, $body);
		$this->repository->changePassword($body['password'], $body['new_password']);
	}

	public function create($data) {
		$securityEntity = new SecurityEntity();
		$securityEntity->load([
			'id' => $data['id'],
			'email' => $data['email'],
			'password_hash' => Yii::$app->security->generatePasswordHash($data['password']),
			'token' => $this->repository->generateUniqueToken(),
		]);
		$this->repository->insert($securityEntity);
	}
	
}
