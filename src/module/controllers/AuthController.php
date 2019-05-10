<?php
namespace yii2module\account\module\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii2lab\applicationTemplate\common\enums\ApplicationPermissionEnum;
use yii2rails\extension\web\helpers\Behavior;
use yii2module\account\domain\v3\forms\LoginForm;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\navigation\domain\widgets\Alert;
use yii2module\account\domain\v3\helpers\AuthHelper;
use yii\web\Response;

/**
 * AuthController controller
 */
class AuthController extends Controller
{
	public $defaultAction = 'login';

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'actions' => ['logout', 'get-token'],
						'allow' => true,
						'roles' => ['@'],
					],
					[
						'actions' => ['login'],
						'allow' => true,
						'roles' => ['?'],
					],
				],
			],
			'verb' => Behavior::verb([
				'logout' => ['post'],
			]),
		];
	}

	/**
	 * Logs in a user.
	 */
	public function actionLogin()
	{
		$form = new LoginForm();
		$body = Yii::$app->request->post();
		$isValid = $form->load($body) && $form->validate();
		if ($isValid) {
			try {
				\App::$domain->account->auth->authenticationFromWeb($form);
				if(!$this->isBackendAccessAllowed()) {
					\App::$domain->account->auth->logout();
					\App::$domain->navigation->alert->create(['account/auth', 'login_access_error'], Alert::TYPE_DANGER);
					return $this->goHome();
				}
				\App::$domain->navigation->alert->create(['account/auth', 'login_success'], Alert::TYPE_SUCCESS);
				return $this->goBack();
			} catch(UnprocessableEntityHttpException $e) {
				$form->addErrorsFromException($e);
			}
		}
		
		return $this->render('login', [
			'model' => $form,
		]);
	}

	/**
	 * Logs out the current user.
	 */
	public function actionLogout($redirect = null)
	{
		\App::$domain->account->auth->logout();
		\App::$domain->navigation->alert->create(['account/auth', 'logout_success'], Alert::TYPE_SUCCESS);
		if($redirect) {
            return $this->redirect([SL . $redirect]);
        } else {
            return $this->goHome();
        }
	}

    public function actionGetToken()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return AuthHelper::getTokenString();
    }

	private function isBackendAccessAllowed()
	{
		if(APP != BACKEND) {
			return true;
		}
		if (Yii::$app->user->can(ApplicationPermissionEnum::BACKEND_ALL)) {
			return true;
		}
		return false;
	}
	
}
