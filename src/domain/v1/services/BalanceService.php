<?php

namespace yii2module\account\domain\v1\services;

use yii2lab\domain\interfaces\repositories\ReadInterface;
use yii2lab\domain\services\base\BaseService;
use yii2woop\common\components\RBAC;
use yii2woop\common\components\RBACRoles;

/**
 * Class BalanceService
 *
 * @package yii2module\account\domain\v1\services
 *
 * @property ReadInterface $repository
 */
class BalanceService extends BaseService {
	
	public function oneSelf() {
		$balance = $this->repository->all();
		$this->checkAccess($balance);
		return $balance;
	}
	
	private function checkAccess($balance) {
		$isSimpleUser = RBAC::checkAccess(RBACRoles::UNKNOWN_USER) || RBAC::checkAccess(RBACRoles::USER);
		if ($isSimpleUser && $balance->active < 0) {
			$balance->active = 0;
		}
	}
	
}
