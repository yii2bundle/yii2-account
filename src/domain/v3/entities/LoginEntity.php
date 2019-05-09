<?php

namespace yii2module\account\domain\v3\entities;

use yii2rails\domain\data\Query;
use yii2rails\extension\arrayTools\helpers\ArrayIterator;
use yii2rails\extension\common\enums\StatusEnum;
use yubundle\staff\domain\v1\entities\CompanyEntity;
use yii\base\Behavior;
use yii\base\Event;
use yii\base\ModelEvent;
use yii2lab\rbac\domain\entities\AssignmentEntity;
use yii2rails\domain\behaviors\entity\TimeValueFilter;
use yubundle\user\domain\v1\entities\PersonEntity;
use yii\helpers\ArrayHelper;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\values\TimeValue;
use yii2module\account\domain\v3\entities\SecurityEntity;
use yii2module\account\domain\v3\interfaces\entities\LoginEntityInterface;

/**
 * Class LoginEntity
 *
 * @package yii2module\account\domain\v3\entities
 *
 * @property integer          $id
 * @property string           $login
 * @property integer          $status
 * @property string           $token
 * @property array            $roles
 * @property string           $username
 * @property string           $created_at
 *
 * @property SecurityEntity   $security
 * @property integer $person_id
 * @property integer $company_id
 * @property PersonEntity $person
 * @property CompanyEntity $company
 * @property AssignmentEntity[] $assignments
 * @property string           $password
 */
class LoginEntity extends BaseEntity implements LoginEntityInterface {
	
	protected $id;
	protected $login;
	protected $status = StatusEnum::ENABLE;
	protected $roles;
	protected $token;
	protected $created_at;
    //protected $password;
	//protected $person_id;
    //protected $company_id;

    //protected $security;
	//protected $person;
    //protected $company;
    protected $assignments;
	protected $contacts;

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['password']);
        return $fields;
    }

    public function behaviors() {
        return [
            [
                'class' => TimeValueFilter::class,
            ],
        ];
    }

	public function fieldType() {
        return[
			'id' => 'integer',
            'login' => 'string',
            'status' => 'integer',
            'token' => 'string',
            //'person_id' => 'integer',
            //'company_id' => 'integer',
            'created_at' => TimeValue::class,
            'security' => SecurityEntity::class,
            //'person' => PersonEntity::class,
            //'company' => CompanyEntity::class,
            'assignments' => [
                'type' => AssignmentEntity::class,
                'isCollection' => true,
            ],
		];
	}
	
	public function rules() {
		return [
			[['login', 'status'], 'trim'],
			[['login', 'status'], 'required'],
			[['status', /*'person_id', 'company_id'*/], 'integer'],
		];
	}

	public function getUsername() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = mb_strtolower($login);
    }
    
	public function getContacts() {
		if(empty($this->contacts)) {
			return null;
		}
		//return $this->contacts;
		$iterator = new ArrayIterator;
		$iterator->setCollection($this->contacts);
		$query = new Query;
		$query->andWhere([
			'is_main' => 1,
			'status' => 1,
		]);
		$collection = $iterator->all($query);
		
		return ArrayHelper::map($collection, 'type', 'data');
		//d($collection);
		//return ArrayHelper::getColumn($this->assignments, 'item_name');
	}
	
	public function getRoles() {
        if(isset($this->roles)) {
            return $this->roles;
        }
        if(!isset($this->assignments)) {
            return null;
        }
        return ArrayHelper::getColumn($this->assignments, 'item_name');
    }

	public static function findIdentity($id) {
		return \App::$domain->account->login->oneById($id);
	}
	
	public static function findIdentityByAccessToken($token, $type = null) {
	}
	
	public function getId() {
		return intval($this->id);
	}

	public function getToken() {
		return $this->getAuthKey();
	}
	
	public function getAuthKey() {
		if(isset($this->security)) {
			return $this->security->token;
		} else {
			return $this->token;
		}
	}
	
	public function validateAuthKey($authKey) {
		return $this->getAuthKey() === $authKey;
	}
	
}
