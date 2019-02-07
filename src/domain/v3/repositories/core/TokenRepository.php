<?php

namespace yii2module\account\domain\v3\repositories\core;

use yii2module\account\domain\v3\interfaces\repositories\TokenInterface;
use yii2rails\domain\repositories\BaseRepository;

/**
 * Class TokenRepository
 * 
 * @package yii2module\account\domain\v3\repositories\core
 * 
 * @property-read \yii2module\account\domain\v3\Domain $domain
 */
class TokenRepository extends BaseRepository implements TokenInterface {

	protected $schemaClass;

}