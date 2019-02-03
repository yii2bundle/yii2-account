<?php

namespace yii2module\account\api\v1\controllers;

use Yii;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\web\helpers\Behavior;
use yii2rails\extension\web\helpers\ClientHelper;
use yii2lab\rest\domain\rest\Controller;
use yii2module\account\console\forms\PseudoLoginForm;



/**
 * Class AuthController
 *
 * @package yii2module\account\api\v1\controllers
 *
 * @property AuthService $service
 */
class AuthController extends Controller {
	
	public $service = 'account.auth';
	
	public function format() {
		return [
			'profile' => [
				'sex' => 'boolean',
			],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'authenticator' => Behavior::auth(['info']),
		];
	}
	
	/**
	 * @inheritdoc
	 */
	protected function verbs() {
		return [
			'login' => ['POST'],
			'info' => ['GET'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'info' => [
				'class' => 'yii2lab\rest\domain\rest\UniAction',
				'service' => 'account.auth',
				'successStatusCode' => 200,
				'serviceMethod' => 'getIdentity',
			],
		];
	}
	
	public function actionLogin() {
		$body = Yii::$app->request->getBodyParams();
		try {
			$ip = ClientHelper::ip();
			$entity = $this->service->authentication($body['login'], $body['password'], $ip);
			Yii::$app->response->headers->set('Authorization', $entity->token);
			return $entity;
		} catch(UnprocessableEntityHttpException $e) {
			Yii::$app->response->setStatusCode(422);
			$response = $e->getErrors();
			return $response;
		}
	}
	
	public function actionPseudo()
	{
		$body = Yii::$app->request->getBodyParams();
		try {
			Helper::validateForm(PseudoLoginForm::class, $body);
			$address = ClientHelper::ip();
			$entity = \App::$domain->account->authPseudo->authentication($body['login'], $address, $body['email'], !empty($body['parentLogin']) ? $body['parentLogin'] : null);
			return ['token' => $entity->token];
		} catch(UnprocessableEntityHttpException $e) {
			Yii::$app->response->setStatusCode(422);
			$response = $e->getErrors();
			return $response;
		}
	}
}