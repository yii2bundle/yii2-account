<?php

namespace yii2module\account\domain\v2\services;

use Yii;
use yii\authclient\BaseOAuth;
use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii2lab\app\domain\helpers\EnvService;
use yii2lab\domain\enums\Driver;
use yii2lab\domain\helpers\factory\RepositoryFactoryHelper;
use yii2module\account\domain\v2\entities\LoginEntity;
use yii2module\account\domain\v2\interfaces\services\OauthInterface;
use yii2lab\domain\services\base\BaseService;

/**
 * Class OauthService
 * 
 * @package yii2module\account\domain\v2\services
 * 
 * @property-read \yii2module\account\domain\v2\Domain $domain
 */
class OauthService extends BaseService implements OauthInterface {
	
	public $defaultRoles = [
		'rOauth',
		'rUser',
	];
	
	/** @var \yii2module\account\domain\v2\interfaces\repositories\LoginInterface */
	private $_arLoginRepository;
	
	public function init() {
		$profiles = EnvService::get('authclient.profiles');
		if($profiles) {
			Yii::$app->set('authClientCollection', [
				'class' => 'yii\authclient\Collection',
				'clients' => $profiles,
			]);
		}
		try {
			/** @var \yii2module\account\domain\v2\interfaces\repositories\LoginInterface $arLoginRepository */
			$this->_arLoginRepository = RepositoryFactoryHelper::createObject('login', Driver::ACTIVE_RECORD, \App::$domain->account);
		} catch(\yii\db\Exception $e) {
		
		}
		parent::init();
	}
	
	public function isEnabled() : bool {
		return Yii::$app->has('authClientCollection');
	}
	
	public function oneById($id): IdentityInterface {
		/** @var LoginEntity $loginEntity */
		$loginEntity = $this->_arLoginRepository->oneById($id);
		$loginEntity->roles = $this->defaultRoles;
		return $loginEntity;
	}
	
	public function authByClient(BaseOAuth $client) {
		$loginEntity = $this->forgeAccount($client);
		\App::$domain->account->auth->login($loginEntity, true);
	}
	
	private function forgeAccount(BaseOAuth $client) : IdentityInterface {
		try {
			$loginEntity = $this->oneByClient($client);
		} catch(NotFoundHttpException $e) {
			$loginEntity = $this->insert($client);
		}
		return $loginEntity;
	}
	
	private function oneByClient(BaseOAuth $client): IdentityInterface {
		$login = $this->generateLogin($client);
		/** @var LoginEntity $loginEntity */
		$loginEntity = $this->_arLoginRepository->oneByLogin($login);
		return $loginEntity;
	}
	
	private function insert(BaseOAuth $client) : IdentityInterface {
		$loginEntity = new LoginEntity;
		$loginEntity->login = $this->generateLogin($client);
		$this->_arLoginRepository->insert($loginEntity);
		return $loginEntity;
	}
	
	private function generateLogin(BaseOAuth $client) : string {
		$login = $client->userAttributes['login'] . '@' . $client->getId();
		return $login;
	}
	
}
