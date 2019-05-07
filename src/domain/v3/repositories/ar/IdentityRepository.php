<?php

namespace yii2module\account\domain\v3\repositories\ar;

use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2module\account\domain\v3\interfaces\repositories\IdentityInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class IdentityRepository
 * 
 * @package yii2module\account\domain\v3\repositories\ar
 * 
 * @property-read \yii2module\account\domain\v3\Domain $domain
 */
class IdentityRepository extends BaseActiveArRepository implements IdentityInterface {

	protected $schemaClass = true;

    public function tableName()
    {
        return 'user_login';
    }

}
