<?php

namespace yii2module\account\domain\v1\repositories\core;

use yii2lab\extension\core\domain\repositories\base\BaseActiveCoreRepository;

class BalanceRepository extends BaseActiveCoreRepository {
	
	public $baseUri = 'balance';
	public $version = 'v4';
	
}