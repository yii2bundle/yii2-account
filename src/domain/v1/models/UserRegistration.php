<?php
namespace yii2module\account\domain\v1\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii2lab\extension\common\helpers\Helper;

/**
 * User model
 *
 * @property string $login
 * @property string $email
 * @property string $activation_code
 * @property string $created_at
 * @property string $update_time
 */
class UserRegistration extends ActiveRecord 
{

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%user_registration}}';
	}
	
	public static function primaryKey()
	{
		return ['login'];
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
	
	public function fields()
	{
		$fields = parent::fields();
		$fields['created_at'] = function () {
			return Helper::timeForApi($this->created_at);
		};
		return $fields;
	}
	
}
