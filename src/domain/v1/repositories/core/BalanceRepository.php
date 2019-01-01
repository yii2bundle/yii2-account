<?php

namespace yii2module\account\domain\v1\repositories\core;

use yii2lab\domain\repositories\ActiveCoreRepository;

class BalanceRepository extends ActiveCoreRepository {
	
	public $baseUri = 'balance';
	public $version = 'v4';
	
}