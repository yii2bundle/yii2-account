<?php

namespace yii2bundle\account\domain\v3\filters\auth;

use Yii;
use yii\filters\auth\AuthMethod;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\extension\console\helpers\Error;
use yii2rails\extension\console\helpers\input\Enter;
use yii2rails\extension\enum\enums\TimeEnum;
use yii2bundle\account\domain\v3\entities\LoginEntity;
use yii2bundle\account\domain\v3\forms\LoginForm;

class ConsoleAuth extends AuthMethod
{
	
	const IDENTITY_CACHE_KEY = 'SECURED_CONSOLE_IDENTITY_Ah1M9R8ai50uFDOdhfZHmEXK7mMZC641';
	
	/**
	 * @inheritdoc
	 */
	public function authenticate($user, $request, $response)
	{
		$identity = null;
		if(Yii::$app->user->isGuest) {
			$identity = $this->getIdentity();
			if(!$identity instanceof IdentityInterface) {
				$this->handleFailure(null);
			}
			\App::$domain->account->auth->login($identity, TimeEnum::SECOND_PER_MINUTE);
		}
		return $identity;
	}
	
	public function handleFailure($response)
	{
		Error::fatal('You not authorized!');
	}
	
	public function beforeAction($action) {
		$response = $this->response ?: Yii::$app->getResponse();
		
		try {
			$identity = $this->authenticate(
				null,
				$this->request ?: Yii::$app->getRequest(),
				$response
			);
		} catch (UnauthorizedHttpException $e) {
			
			if ($this->isOptional($action)) {
				return true;
			}
			throw $e;
		}
		
		if ($identity !== null || $this->isOptional($action)) {
			return true;
		}
		
		$this->challenge($response);
		$this->handleFailure($response);
		
		return false;
	}
	
	public function auth() {
		$identity = null;
		$default = null;
		$form = new LoginForm;
		$data = Enter::form($form, $default, LoginForm::SCENARIO_SIMPLE);
		try {
			$identity = \App::$domain->account->auth->authentication($data['login'], $data['password']);
		} catch(UnprocessableEntityHttpException $e) {
			$this->handleFailure(null);
		}
		return $identity;
	}
	
	public function getIdentity() {
		$identity = Yii::$app->cache->get(self::IDENTITY_CACHE_KEY);
		if(!$identity) {
			$identity = $this->auth();
		} else {
			//$identity = \App::$domain->account->auth->authenticationByToken($identity->token);
		}
		if($identity) {
			if(!$identity instanceof IdentityInterface) {
				$identity = ArrayHelper::toArray($identity);
				$identity = new LoginEntity($identity);
			}
		}
		Yii::$app->cache->set(self::IDENTITY_CACHE_KEY, $identity);
		return $identity;
	}
	
	/**
	 * @inheritdoc
	 */
	public function challenge($response)
	{
	}
}
