<?php

namespace yii2module\account\domain\v1\models;

use yii\db\ActiveRecord;

class UserAssignment extends ActiveRecord
{

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return '{{%user_assignment}}';
	}
	
}
