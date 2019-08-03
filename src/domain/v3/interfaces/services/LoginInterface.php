<?php

namespace yii2bundle\account\domain\v3\interfaces\services;

use yii2bundle\account\domain\v3\entities\IdentityEntity;
use yii2bundle\account\domain\v3\interfaces\entities\LoginEntityInterface;
use yii2rails\domain\data\Query;
use yii2rails\domain\interfaces\services\CrudInterface;
use yii2rails\extension\common\exceptions\AlreadyExistsException;
use yii2bundle\account\domain\v3\entities\LoginEntity;
use yii2bundle\account\domain\v3\forms\registration\PersonInfoForm;

/**
 * Interface LoginInterface
 *
 * @package yii2bundle\account\domain\v3\interfaces\services
 *
 * @property integer $defaultStatus
 * @property string $defaultRole
 * @property array $prefixList
 * @property array $forbiddenStatusList
 */
interface LoginInterface extends CrudInterface {
	
	/**
	 * @param string $phone
	 *
	 * @throws AlreadyExistsException
	 */
	//public function checkExistsPhone(string $phone);
	
	/**
	 * @param string $login
	 *
	 * @throws AlreadyExistsException
	 */
	//public function checkExistsLogin(string $login);
	//public function oneByPhone(string $phone, Query $query = null);
	public function oneByAny(string $any, Query $query = null) : IdentityEntity;
	public function createWeb(PersonInfoForm $model);

    public function oneByLogin($login, Query $query = null) : LoginEntity;
    //public function oneByPersonId(int $personId, Query $query = null) : LoginEntity;
	public function isValidLogin($login);
	public function normalizeLogin($login);
	//public function isExistsByLogin($login);
	public function isForbiddenByStatus($status);
	
}
