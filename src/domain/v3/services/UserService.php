<?php

namespace yii2module\account\domain\v3\services;

use Yii;
use yii2module\account\domain\v3\interfaces\services\UserInterface;
use yii2rails\domain\services\base\BaseService;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\web\ForbiddenHttpException;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yii2module\account\domain\v3\helpers\LoginTypeHelper;
use yii2rails\domain\BaseEntity;
use yii2rails\domain\data\Query;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\ErrorCollection;
use yii2rails\domain\helpers\Helper;
use yii2rails\domain\traits\MethodEventTrait;
use yii2rails\extension\common\helpers\StringHelper;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2rails\extension\yii\helpers\ArrayHelper;
use yii2module\account\domain\v3\behaviors\UserActivityFilter;
use yii2module\account\domain\v3\enums\AccountEventEnum;
use yii2module\account\domain\v3\filters\token\BaseTokenFilter;
use yii2module\account\domain\v3\filters\token\DefaultFilter;
use yii2module\account\domain\v3\forms\LoginForm;
use yii2module\account\domain\v3\helpers\AuthHelper;
use yii2module\account\domain\v3\helpers\TokenHelper;
use yii2module\account\domain\v3\interfaces\services\AuthInterface;
use yii\web\ServerErrorHttpException;
use yii2module\account\domain\v3\entities\LoginEntity;

/**
 * Class UserService
 * 
 * @package yii2module\account\domain\v3\services
 * 
 * @property-read \yii2module\account\domain\v3\Domain $domain
 * @property-read \yii2module\account\domain\v3\interfaces\repositories\UserInterface $repository
 */
class UserService extends BaseService implements UserInterface {
	
	public $rememberExpire = TimeEnum::SECOND_PER_DAY * 30;
	private $_identity = null;
	
	public function oneSelf(Query $query = null) : LoginEntity {
		$query = Query::forge($query);
		return \App::$domain->account->login->oneById($this->getIdentity()->id, $query);
	}
	
	public function isGuest() : bool {
		return Yii::$app->user->isGuest;
	}
	
	public function getIdentity() : LoginEntity {
		if(isset(Yii::$app->user)) {
			if(Yii::$app->user->isGuest) {
				$this->breakSession();
			}
			return Yii::$app->user->identity;
		}
		if($this->_identity === null) {
			$this->breakSession();
		}
		return $this->_identity;
	}
	
	public function login(IdentityInterface $loginEntity, bool $rememberMe = false) {
		if(empty($loginEntity)) {
			return null;
		}
		$duration = $rememberMe ? $this->rememberExpire : 0;
		if(isset(Yii::$app->user)) {
			Yii::$app->user->login($loginEntity, $duration);
		}
		$this->_identity = $loginEntity;
		AuthHelper::setToken($loginEntity->token);
	}
	
	public function logout() {
		Yii::$app->user->logout();
		AuthHelper::setToken('');
	}
	
	public function denyAccess() {
		if(Yii::$app->user->getIsGuest()) {
			$this->breakSession();
		} else {
			throw new ForbiddenHttpException();
		}
	}
	
	public function loginRequired() {
		try {
			Yii::$app->user->loginRequired();
		} catch(InvalidConfigException $e) {
			return;
		}
	}
	
	public function breakSession() {
		if(APP == CONSOLE) {
			return;
		}
		if(APP == API) {
			throw new UnauthorizedHttpException;
		} else {
			$this->logout();
			Yii::$app->session->destroy();
			Yii::$app->response->cookies->removeAll();
			$this->loginRequired();
		}
	}
	
}
