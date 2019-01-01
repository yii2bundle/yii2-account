<?php

namespace yii2module\account\domain\v2\repositories\tps;

use yii2module\account\domain\v2\interfaces\repositories\TokenInterface;
use yii2lab\domain\repositories\BaseRepository;

/**
 * Class TokenRepository
 * 
 * @package yii2module\account\domain\v2\repositories\tps
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 */
class TokenRepository extends BaseRepository implements TokenInterface {

	protected $schemaClass;

}
