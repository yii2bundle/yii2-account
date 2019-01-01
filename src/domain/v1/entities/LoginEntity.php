<?php

namespace yii2module\account\domain\v1\entities;

use Yii;
use yii\helpers\ArrayHelper;
use yii2lab\app\domain\helpers\EnvService;
use yii2lab\domain\BaseEntity;
use yii\web\IdentityInterface;
use yii2module\account\domain\v1\helpers\LoginHelper;

/**
 * Class LoginEntity
 *
 * @package yii2module\account\domain\v1\entities
 *
 * @property integer $id
 * @property string $login
 * @property string $email
 * @property integer $subject_type
 * @property string $token
 * @property integer $parent_id
 * @property string $iin_fixed
 * @property integer $creation_date
 * @property string $password_hash
 * @property array $roles
 * @property BaseEntity $profile
 * @property BaseEntity $balance
 * @property BaseEntity $address
 * @property string $avatar
 * @property string $username
 * @property string $created_at
 */
class LoginEntity extends BaseEntity implements IdentityInterface {

	protected $id;
	protected $login;
	protected $email;
	//protected $subject_type = 3000;
	protected $token;
	//protected $parent_id;
	//protected $iin_fixed = false;
	protected $status;
	//protected $creation_date;
	protected $password_hash;
	protected $roles;
	protected $profile;
	protected $balance;
	/** @var $address
	 * @deprecated */
	protected $address;
	protected $password;
	private $isShowToken = false;
	
	public function rules() {
		return [
			[['login', 'status'], 'trim'],
			[['login', 'status'], 'required'],
			[['status'], 'integer'],
		];
	}
	
	public function getAvatar() {
		$avatar = EnvService::getUrl('frontend', 'images/avatars/_default.jpg');
		return $avatar;
	}
	
	public function getCreatedAt() {
		return $this->creation_date;
	}
	
	public function getBalance() {
		if(!empty($this->balance)) {
			return $this->balance;
		}
		return \App::$domain->account->auth->getBalance();
	}

	public function getUsername() {
		return LoginHelper::format($this->login);
	}

	public function getIinFixed() {
		if(empty($this->iin_fixed)) {
			return  false;
		}
		return $this->iin_fixed;
	}
	
	public function setRoles($value) {
		if(!empty($value)) {
			$this->roles = ArrayHelper::toArray($value);
		}
	}
	
	public function fieldType() {
		$fieldTypeConfig = [
			'id' => 'integer',
			'parent_id' => 'integer',
			'subject_type' => 'integer',
		];
		return $fieldTypeConfig;
	}
	
	public function showToken() {
		$this->isShowToken = true;
	}

	public static function findIdentity($id) {}

	public static function findIdentityByAccessToken($token, $type = null) {}

	public function getId() {
		return intval($this->id);
	}
	
	public function getToken() {
		return $this->getAuthKey();
	}
	
	public function getAuthKey() {
		return $this->token;
	}

	public function validateAuthKey($authKey) {
		return $this->getAuthKey() === $authKey;
	}

	public function fields()
	{
		$fields = parent::fields();
		unset($fields['password_hash']);
		if(!$this->isShowToken) {
			unset($fields['token']);
		}
		unset($fields['password']);
		return $fields;
	}
}
