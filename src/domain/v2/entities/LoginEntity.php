<?php

namespace yii2module\account\domain\v2\entities;

use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii2lab\app\domain\helpers\EnvService;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\values\TimeValue;
use yii2lab\rbac\domain\entities\AssignmentEntity;
use yii2module\account\domain\v2\helpers\LoginHelper;
use yii2module\account\domain\v2\interfaces\entities\LoginEntityInterface;

/**
 * Class LoginEntity
 *
 * @package yii2module\account\domain\v2\entities
 *
 * @property integer          $id
 * @property string           $login
 * @property integer          $status
 * @property string           $token
 * @property array            $roles
 * @property string           $username
 * @property string           $created_at
 * @property SecurityEntity   $security
 * @property AssignmentEntity $assignments
 * @property-read string      $email
 * @property string           $password
 * @property string           $parent_login
 * @property string           $subject_type
 */
class LoginEntity extends BaseEntity implements LoginEntityInterface
{
	
	protected $id;
	protected $login;
	protected $status;
	protected $roles;
	protected $security;
	protected $assignments;
	protected $token;
	protected $email;
	protected $created_at;
	protected $password;
	
	public function init()
	{
		parent::init();
		$this->created_at = new TimeValue;
		$this->created_at->setNow();
	}
	
	public function fieldType()
	{
		$fieldTypeConfig = [
			'id' => 'integer',
			'parent_id' => 'integer',
			'subject_type' => 'integer',
			'created_at' => TimeValue::class,
		];
		return $fieldTypeConfig;
	}
	
	public function rules()
	{
		return [
			[['login', 'status'], 'trim'],
			[['login', 'status'], 'required'],
			[['status'], 'integer'],
		];
	}
	
	public function getAvatar()
	{
		$avatar = EnvService::getUrl('frontend', 'images/avatars/_default.jpg');
		return $avatar;
	}
	
	public function getUsername()
	{
		return LoginHelper::format($this->login);
	}
	
	public function getIinFixed()
	{
		if(empty($this->iin_fixed)) {
			return false;
		}
		return $this->iin_fixed;
	}
	
	public function setRoles($value)
	{
		if(!empty($value)) {
			$this->roles = ArrayHelper::toArray($value);
		}
	}
	
	public static function findIdentity($id)
	{
	}
	
	public static function findIdentityByAccessToken($token, $type = null)
	{
	}
	
	public function getId()
	{
		return intval($this->id);
	}
	
	public function getToken()
	{
		return $this->getAuthKey();
	}
	
	/**
	 * @return string
	 * @deprecated после перехода на security выпилить
	 */
	public function getEmail()
	{
		if(!$this->security instanceof SecurityEntity) {
			return $this->email;
		}
		return $this->security->email;
	}
	
	public function getAuthKey()
	{
		if(!$this->security instanceof SecurityEntity) {
			return $this->token;
		}
		return $this->security->token;
	}
	
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}
	
	public function fields()
	{
		$fields = parent::fields();
		unset($fields['security']);
		$fields['token'] = 'token';
		if(empty($this->password)) {
			unset($fields['password']);
		}
		return $fields;
	}
}
