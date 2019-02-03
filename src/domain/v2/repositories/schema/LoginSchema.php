<?php

namespace yii2module\account\domain\v2\repositories\schema;

use yii2rails\domain\enums\RelationEnum;
use yii2rails\domain\repositories\relations\BaseSchema;

class LoginSchema extends BaseSchema {
	
	public function uniqueFields() {
		return [
			['login'],
		];
	}
	
	public function relations() {
		return [
			'security' => [
				'type' => RelationEnum::ONE,
				'field' => 'id',
				'foreign' => [
					'id' => 'account.security',
					'field' => 'id',
				],
			],
			'assignments' => [
				'type' => RelationEnum::MANY,
				'field' => 'id',
				'foreign' => [
					'id' => 'account.assignment',
					'field' => 'user_id',
				],
			],
		];
	}
	
}
