<?php

namespace yii2bundle\account\domain\v3\repositories\schema;

use yii2rails\domain\enums\RelationEnum;
use yii2rails\domain\repositories\relations\BaseSchema;

/**
 * Class IdentitySchema
 * 
 * @package yii2bundle\account\domain\v3\repositories\schema
 * 
 */
class IdentitySchema extends BaseSchema {

    public function uniqueFields() {
        return [
            ['login'],
        ];
    }

    public function relations() {
        return [
        	
            /*'person' => [
                'type' => RelationEnum::ONE,
                'field' => 'person_id',
                'foreign' => [
                    'id' => 'user.person',
                    'field' => 'id',
                ],
            ],
            'company' => [
                'type' => RelationEnum::ONE,
                'field' => 'company_id',
                'foreign' => [
                    'id' => 'staff.company',
                    'field' => 'id',
                ],
            ],
            'security' => [
                'type' => RelationEnum::ONE,
                'field' => 'id',
                'foreign' => [
                    'id' => 'account.security',
                    'field' => 'id',
                ],
            ],*/
	        'contacts' => [
		        'type' => RelationEnum::MANY,
		        'field' => 'id',
		        'foreign' => [
			        'id' => 'account.contact',
			        'field' => 'identity_id',
		        ],
	        ],
            'assignments' => [
                'type' => RelationEnum::MANY,
                'field' => 'id',
                'foreign' => [
                    'id' => 'rbac.assignment',
                    'field' => 'user_id',
                ],
            ],
        ];
    }

}
