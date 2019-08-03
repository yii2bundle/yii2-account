<?php

namespace yii2bundle\account\api\v3\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii2lab\rest\domain\rest\Controller;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2rails\domain\helpers\Helper;
use yii2rails\extension\common\exceptions\AlreadyExistsException;
use yii2rails\extension\common\exceptions\CreatedHttpExceptionException;
use yii2rails\extension\web\helpers\Behavior;
use yii2bundle\account\domain\v3\exceptions\ConfirmIncorrectCodeException;
use yii2bundle\account\domain\v3\forms\restorePassword\UpdatePasswordForm;

class RestorePasswordController extends Controller
{
	public $service = 'account.restorePassword';

    public function behaviors()
    {
        return [
            'cors' => Behavior::cors(),
        ];
    }

    public function actions()
    {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

	/**
	 * @inheritdoc
	 */
	protected function verbs()
	{
		return [
            'request-activation-code' => ['POST'],
            'verify-activation-code' => ['POST'],
            'set-password' => ['POST'],
		];
	}

    public function actionRequestActivationCode() {
        $model = new UpdatePasswordForm;
        if(\Yii::$app->request->isPost) {
            Helper::forgeForm($model);
            try {
                \App::$domain->account->restorePassword->requestCode($model);
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
        $model = new UpdatePasswordForm;
        if(\Yii::$app->request->isPost) {
            Helper::forgeForm($model);
            try {
                \App::$domain->account->restorePassword->verifyCode($model);
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

    public function actionSetPassword() {
        $model = new UpdatePasswordForm;
        if(\Yii::$app->request->isPost) {
            $post = Helper::post();
            $post['password_confirm'] = ArrayHelper::getValue($post, 'password');
            Helper::forgeForm($model, $post);
            try {
                \App::$domain->account->restorePassword->verifyCode($model);
                $model->password_confirm = $model->password;
                \App::$domain->account->restorePassword->setNewPassword($model);
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
				'serviceMethod' => 'request',
				'serviceMethodParams' => ['login'],
			],
			'verify-activation-code' => [
				'class' => 'yii2lab\rest\domain\rest\UniAction',
				'successStatusCode' => 204,
				'serviceMethod' => 'checkActivationCode',
				'serviceMethodParams' => ['login', 'activation_code'],
			],
			'reset-password' => [
				'class' => 'yii2lab\rest\domain\rest\UniAction',
				'successStatusCode' => 204,
				'serviceMethod' => 'confirm',
				'serviceMethodParams' => ['login', 'activation_code', 'password'],
			],
		];
	}*/

}