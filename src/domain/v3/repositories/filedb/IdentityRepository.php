<?php

namespace yii2bundle\account\domain\v3\repositories\filedb;

use yii2bundle\account\domain\v3\entities\LoginEntity;
use yii2bundle\account\domain\v3\repositories\traits\LoginTrait;
use yii2rails\domain\data\Query;
use yii2rails\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2bundle\account\domain\v3\interfaces\repositories\IdentityInterface;
use yii2rails\domain\repositories\BaseRepository;
use yii2rails\extension\filedb\repositories\base\BaseActiveFiledbRepository;

/**
 * Class IdentityRepository
 * 
 * @package yii2bundle\account\domain\v3\repositories\ar
 * 
 * @property-read \yii2bundle\account\domain\v3\Domain $domain
 */
class IdentityRepository extends BaseActiveFiledbRepository implements IdentityInterface {

	use LoginTrait;
	
	protected $schemaClass = true;
}
