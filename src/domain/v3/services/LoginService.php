<?php

namespace yii2module\account\domain\v3\services;

use App;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii2module\account\domain\v3\entities\IdentityEntity;
use yii2module\account\domain\v3\strategies\login\LoginContext;
use yii2rails\app\domain\helpers\EnvService;
use yii2rails\domain\behaviors\query\QueryFilter;
use yii2rails\domain\data\Query;
use yii2module\account\domain\v3\entities\LoginEntity;
use yii2module\account\domain\v3\forms\registration\PersonInfoForm;
use yii2rails\extension\common\enums\StatusEnum;
use yii\web\NotFoundHttpException;
use yii2rails\domain\exceptions\UnprocessableEntityHttpException;
use yii2module\account\domain\v3\interfaces\services\LoginInterface;
use yubundle\user\domain\v1\entities\ClientEntity;
use yubundle\user\domain\v1\entities\PersonEntity;
use yii2rails\domain\services\base\BaseActiveService;
use yii2rails\extension\common\helpers\InstanceHelper;
use yii2module\account\domain\v3\filters\login\LoginValidator;
use yii2module\account\domain\v3\interfaces\LoginValidatorInterface;
use yii2module\account\domain\v3\strategies\login\handlers\EmailStrategy;
use yii2module\account\domain\v3\strategies\login\handlers\LoginStrategy;
use yii2module\account\domain\v3\strategies\login\handlers\PhoneStrategy;
use yii2module\account\domain\v3\strategies\login\handlers\TokenStrategy;

/**
 * Class LoginService
 *
 * @package yii2module\account\domain\v3\services
 *
 * @property \yii2module\account\domain\v3\interfaces\repositories\LoginInterface $repository
 * @property \yii2module\account\domain\v3\Domain $domain
 */
class LoginService extends BaseActiveService implements LoginInterface {
	
	public $relations = [];
	//public $prefixList = [];
	public $defaultRole;
	public $defaultStatus;
	public $forbiddenStatusList;
	public $loginStrategyDefinitions = [
		'login' => LoginStrategy::class,
		'phone' => PhoneStrategy::class,
		'email' => EmailStrategy::class,
		'token' => TokenStrategy::class,
	];
	
	/** @var LoginValidatorInterface|array|string $validator */
	public $loginValidator = LoginValidator::class;
	
	/*public function oneByPhone(string $phone, Query $query = null) {
		return $this->repository->oneByPhone($phone, $query);
	}*/
	
	public function behaviors()
	{
		return [
			[
				'class' => QueryFilter::class,
				'method' => 'with',
				'params' => 'assignments',
			],
		];
	}
	
	public function getRepository($name = null) {
		return parent::getRepository('identity');
	}
	
	public function createWeb(PersonInfoForm $model) {
		$model->scenario = PersonInfoForm::SCENARIO_CREATE_ACCOUNT;
		if(!$model->validate()) {
			throw new UnprocessableEntityHttpException($model);
		}

		$isExistsPhone = App::$domain->account->contact->isExistsByData($model->phone, 'phone');
		if($isExistsPhone) {
			$model->addError('phone', Yii::t('account/registration', 'user_already_exists_and_activated'));
			throw new UnprocessableEntityHttpException($model);
		}

        if(App::$domain->account->login->isExistsByLogin($model->login)) {
			$model->addError('login', Yii::t('account/registration', 'user_already_exists_and_activated'));
			throw new UnprocessableEntityHttpException($model);
		}
		
        /** @var PersonEntity $personEntity */
		$data = $model->toArray();
        $data['company_id'] = EnvService::get('account.login.defaultCompanyId');
		//$personEntity = $this->createPerson($data);
		//$this->createClient($personEntity);
		
		$loginEntity = App::$domain->account->identity->create([
			'login' => $data['login'],
		]);
		App::$domain->account->security->make($loginEntity->id, $data['password']);
		App::$domain->account->contact->create([
			'identity_id' => $loginEntity->id,
			'type' => 'phone',
			'data' => $model->phone,
			'is_main' => true,
		]);
		return $loginEntity;
	}
	
	public function oneByAny(string $any, Query $query = null) : IdentityEntity {
		$loginContext = new LoginContext;
		$loginContext->setStrategyDefinitions($this->loginStrategyDefinitions);
		try {
			$loginId = $loginContext->identityIdByAny($any);
		} catch(\Exception $e) {
			throw new NotFoundHttpException($e->getMessage(), 0, $e);
		}
		$loginEntity = \App::$domain->account->repositories->identity->oneById($loginId, $query);
		return $loginEntity;
	}
	
	public function oneById($id, Query $query = null) {
		try {
			$loginEntity = parent::oneById($id, $query);
		} catch(NotFoundHttpException $e) {
			if(\App::$domain->account->oauth->isEnabled()) {
				$loginEntity = \App::$domain->account->oauth->oneById($id);
			} else {
				throw $e;
			}
		}
		return $loginEntity;
	}
	
	public function isExistsByLogin($login) {
		try {
			$this->repository->oneByLogin($login);
			return true;
		} catch(NotFoundHttpException $e) {
			return false;
		}
	}
	
	/**
	 * @param $login
	 *
	 * @return \yii2module\account\domain\v3\entities\LoginEntity
	 *
	 * @throws NotFoundHttpException
	 */
	public function oneByLogin($login, Query $query = null) : LoginEntity {
		$query = Query::forge($query);
		$query->with('assignments');
		return $this->repository->oneByLogin($login, $query);
	}

   /* public function oneByPersonId(int $personId, Query $query = null) : LoginEntity {
        $query = Query::forge($query);
        $query->andWhere(['person_id' => $personId]);
        return $this->repository->one($query);
    }*/

	public function isValidLogin($login) {
		$loginContext = new LoginContext;
		$loginContext->setStrategyDefinitions($this->loginStrategyDefinitions);
		return $loginContext->isValid($login);
	}
	
	public function normalizeLogin($login) {
		$loginContext = new LoginContext;
		$loginContext->setStrategyDefinitions($this->loginStrategyDefinitions);
		return $loginContext->normalizeLogin($login);
	}
	
	public function isForbiddenByStatus($status) {
		if(empty($this->forbiddenStatusList)) {
			return false;
		}
		return in_array($status, $this->forbiddenStatusList);
	}
	
	/**
	 * @return LoginValidatorInterface
	 */
	/*private function getLoginValidator() {
		$this->loginValidator = InstanceHelper::ensure($this->loginValidator, [], LoginValidatorInterface::class);
		return $this->loginValidator;
	}*/
	
}
