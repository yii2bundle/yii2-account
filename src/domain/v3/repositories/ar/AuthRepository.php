<?php

namespace yii2module\account\domain\v3\repositories\ar;

use yii2module\account\domain\v3\helpers\AuthHelper;
use yii2module\account\domain\v3\interfaces\repositories\AuthInterface;
use yii2module\account\domain\v3\repositories\base\BaseAuthRepository;
use yii2rails\domain\repositories\BaseRepository;
use yii\base\InvalidArgumentException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2module\account\domain\v3\helpers\LoginTypeHelper;
use yii2rails\domain\data\Query;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v3\entities\LoginEntity;
use yii2module\account\domain\v3\entities\SecurityEntity;
use yii2module\account\domain\v3\helpers\TokenHelper;

class AuthRepository extends BaseRepository implements AuthInterface {
	

	
}
