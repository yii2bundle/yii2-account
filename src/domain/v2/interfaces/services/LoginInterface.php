<?php

namespace yii2bundle\account\domain\v2\interfaces\services;

use yii2rails\domain\interfaces\services\CrudInterface;

/**
 * Interface LoginInterface
 *
 * @package yii2bundle\account\domain\v2\interfaces\services
 *
 * @property integer $defaultStatus
 * @property string $defaultRole
 * @property array $prefixList
 * @property array $forbiddenStatusList
 */
interface LoginInterface extends CrudInterface {
	
	public function oneByLogin($login);
	public function isValidLogin($login);
	public function normalizeLogin($login);
	public function isExistsByLogin($login);
	public function isForbiddenByStatus($status);

}