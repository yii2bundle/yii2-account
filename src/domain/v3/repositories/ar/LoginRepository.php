<?php

namespace yii2module\account\domain\v3\repositories\ar;

use domain\mail\v1\entities\BoxEntity;
use domain\mail\v1\entities\DomainEntity;
use yii\base\InvalidArgumentException;
use yii2module\account\domain\v3\entities\ContactEntity;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2module\account\domain\v3\repositories\traits\LoginTrait;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2module\account\domain\v3\entities\LoginEntity;
use yii2module\account\domain\v3\helpers\LoginTypeHelper;
use Yii;
use yii\web\NotFoundHttpException;
use yii2lab\db\domain\helpers\TableHelper;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2module\account\domain\v3\interfaces\repositories\LoginInterface;

/**
 * Class LoginRepository
 *
 * @package yii2module\account\domain\v3\repositories\ar
 *
 * @property-read \yubundle\user\domain\v1\Domain $domain
 */
class LoginRepository extends IdentityRepository implements LoginInterface {

    public function uniqueFields() {
        return ['login'];
    }

    public function insert(BaseEntity $loginEntity) {
		$loginEntity->validate();
		/** @var LoginEntity $loginEntity */
		$this->findUnique($loginEntity);
		$data = [
			'person_id' => $loginEntity->person_id,
			'login' => $loginEntity->login,
            'company_id' => $loginEntity->company_id,
			'password' => Yii::$app->security->generatePasswordHash($loginEntity->password),
		];
        $tableName = TableHelper::getGlobalName($this->tableName());
		Yii::$app->db->createCommand()->insert($tableName, $data)->execute();
		$loginEntity->id = Yii::$app->db->getLastInsertID();
	}
	
}
