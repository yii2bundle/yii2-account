<?php

namespace yii2module\account\domain\v1\repositories\ar;

use Yii;
use yii\rbac\Assignment;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii2lab\domain\BaseEntity;
use yii2lab\domain\data\Query;
use yii2lab\extension\activeRecord\repositories\base\BaseActiveArRepository;
use yii2module\account\domain\v1\entities\LoginEntity;
use yii2module\account\domain\v1\helpers\LoginEntityFactory;
use yii\helpers\ArrayHelper;
use yii2module\account\domain\v1\interfaces\repositories\LoginInterface;
use yii2module\account\domain\v1\models\User;

class PartnerRepository extends BaseActiveArRepository {

	protected $modelClass = 'yii2module\account\domain\v1\models\PartnerPrefixes';

	public function getPrefixByPartnerLogin($partnerLogin) {
		$query = Query::forge();
		$query->where('partner_login', $partnerLogin);
		return $this->one($query);
	}

}