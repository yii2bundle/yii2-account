<?php

namespace yii2bundle\account\api\v3\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii2lab\applicationTemplate\common\enums\ApplicationPermissionEnum;
use yii2lab\rest\domain\rest\Controller;
use yii2bundle\account\domain\v3\exceptions\ConfirmIncorrectCodeException;
use yii2bundle\account\domain\v3\forms\LoginForm;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\common\exceptions\AlreadyExistsException;
use yii2rails\extension\common\exceptions\CreatedHttpExceptionException;
use yii2rails\extension\web\helpers\Behavior;
use yii2bundle\account\domain\v3\forms\registration\PersonInfoForm;
use yii2rails\domain\helpers\ErrorCollection;

class RegistrationController extends Controller {
	
	public $service = 'account.registration';
	
	public function behaviors() {
		return [
			'cors' => Behavior::cors(),
		];
	}
	
	/**
	 * @inheritdoc
	 */
	protected function verbs() {
		return [
			'request-activation-code' => ['POST'],
			'request-activation-code-with-person' => ['POST'],
			'verify-activation-code' => ['POST'],
			'create-account' => ['POST'],
		];
	}
	
	public function actionRequestActivationCode() {
		$model = new PersonInfoForm;
		if(\Yii::$app->request->isPost) {
			Helper::forgeForm($model);
			try {
				\App::$domain->account->registration->requestCode($model);
			} catch (CreatedHttpExceptionException $e) {
				$model->addError('phone', $e->getMessage());
			} catch (AlreadyExistsException $e) {
				$model->addError('phone', $e->getMessage());
			}
		}
		if($model->hasErrors()) {
			throw new UnprocessableEntityHttpException($model);
		}
		Yii::$app->response->setStatusCode(201);
	}

    public function actionRequestActivationCodeWithPerson() {
        $model = new PersonInfoForm;
        if(\Yii::$app->request->isPost) {
            Helper::forgeForm($model);
            try {
                \App::$domain->account->registration->requestCodeWithPersonInfo($model);
            } catch (CreatedHttpExceptionException $e) {
                $model->addError('phone', $e->getMessage());
            } catch (AlreadyExistsException $e) {
                $model->addError('phone', $e->getMessage());
            }
        }
        if($model->hasErrors()) {
            throw new UnprocessableEntityHttpException($model);
        }
        Yii::$app->response->setStatusCode(201);
    }

    public function actionVerifyActivationCode() {
		$model = new PersonInfoForm;
		if(\Yii::$app->request->isPost) {
			Helper::forgeForm($model);
			try {
				\App::$domain->account->registration->verifyCode($model);
			} catch(NotFoundHttpException $e) {
				$model->addError('phone', $e->getMessage());
			} catch(ConfirmIncorrectCodeException $e) {
				$model->addError('activation_code', $e->getMessage());
			}
		}
		if($model->hasErrors()) {
			throw new UnprocessableEntityHttpException($model);
		}
		Yii::$app->response->setStatusCode(204);
	}
	
	public function actionCreateAccount() {
		$model = new PersonInfoForm;
		if(\Yii::$app->request->isPost) {
			$post = Helper::post();
			if (!empty($post['birthday'])){
                list($post['birthday_year'], $post['birthday_month'], $post['birthday_day']) = explode('-', $post['birthday']);
                unset($post['birthday']);
            }

			Helper::forgeForm($model, $post);
			try {

			    if(\App::$domain->account->user->isGuest()) {
                    $isAllow = false;
                } else {
                    $isAllow = \App::$domain->rbac->manager->isAllow([ApplicationPermissionEnum::BACKEND_ALL]);
                }
                if(!$isAllow) {
                    \App::$domain->account->registration->verifyCode($model);
                }
				$model->password_confirm = $model->password;
				\App::$domain->account->registration->createAccountWeb($model);
			} catch(NotFoundHttpException $e) {
				$model->addError('phone', $e->getMessage());
			} catch(ConfirmIncorrectCodeException $e) {
				$model->addError('activation_code', $e->getMessage());
			}
		}
		if($model->hasErrors()) {
			throw new UnprocessableEntityHttpException($model);
		}
		Yii::$app->response->setStatusCode(201);
	}
	
	/**
	 * @inheritdoc
	 */
	/*public function actions() {
		return [
			'request-activation-code' => [
				'class' => 'yii2lab\rest\domain\rest\UniAction',
				'successStatusCode' => 201,
				'serviceMethod' => 'requestActivationCode',
			],
			'verify-activation-code' => [
				'class' => 'yii2lab\rest\domain\rest\UniAction',
				'successStatusCode' => 204,
				'serviceMethod' => 'verifyActivationCode',
			],
			'create-account' => [
				'class' => 'yii2lab\rest\domain\rest\UniAction',
				'successStatusCode' => 201,
				'serviceMethod' => 'createAccount',
			],
		];
	}*/
	
}