<?php
namespace yii2module\account\domain\v1\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * User model
 *
 * @property integer $id
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property string $password write-only password
 */
class User extends ActiveRecord 
{

	public $updated_at;
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%user}}';
	}
	
	public static function primaryKey()
	{
		return ['id'];
	}
	
	public static function findIdentity($id)
	{
		return static::findOne(['id' => $id]);
	}
	
	public function getRoles()
	{
		return explode(',', $this->role);
	}
	
	public function behaviors()
	{
		return [
			'timestamp' => [
				'class' => TimestampBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
				],
				'value' => function() { return date('Y-m-d H:i:s'); },
			],
		];
	}
}
