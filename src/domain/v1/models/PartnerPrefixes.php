<?php
/**
 * Created by PhpStorm.
 * User: amubarak
 * Date: 14.12.2017
 * Time: 17:32
 */

namespace yii2module\account\domain\v1\models;


use yii\db\ActiveRecord;

class PartnerPrefixes  extends ActiveRecord {
	
	public static function tableName() {
		return '{{%partner_prefixes}}';
	}
}