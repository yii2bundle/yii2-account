<?php

namespace yii2module\account\domain\v3\services;

use App;
use Yii;
use yii\helpers\ArrayHelper;
use yii2rails\app\domain\helpers\EnvService;
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
	public $prefixList = [];
	public $defaultRole;
	public $defaultStatus;
	public $forbiddenStatusList;
	
	/** @var LoginValidatorInterface|array|string $validator */
	public $loginValidator = LoginValidator::class;
	
	public function oneByPhone(string $phone, Query $query = null) {
		return $this->repository->oneByPhone($phone, $query);
	}
	
	public function createWeb(PersonInfoForm $model) {
		$model->scenario = PersonInfoForm::SCENARIO_CREATE_ACCOUNT;
		if(!$model->validate()) {
			throw new UnprocessableEntityHttpException($model);
		}

		if(App::$domain->user->person->isExistsByPhone($model->phone)) {
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
		$personEntity = $this->createPerson($data);
		$this->createClient($personEntity);
		$loginEntity = $this->createUser($data, $personEntity);
		return $loginEntity;
	}
	
	private function createPerson(array $data) : PersonEntity {
		$data['birthday'] = $data['birthday_year'] . '-' . $data['birthday_month'] . '-' . $data['birthday_day'];
		$personEntity = App::$domain->user->person->create($data);
		return $personEntity;
	}
	
	private function createClient(PersonEntity $personEntity) : ClientEntity {
		$clientEntity = new ClientEntity;
		$clientEntity->person_id = $personEntity->id;
		$clientEntity->status = StatusEnum::ENABLE;
        \App::$domain->user->repositories->client->insert($clientEntity);
		return $clientEntity;
	}

	private function createUser(array $data, PersonEntity $personEntity) : LoginEntity {
		/** @var LoginEntity $loginEntity */
		$data['person_id'] = $personEntity->id;
		$loginEntity = $this->domain->factory->entity->create($this->id, $data);
		$loginEntity->company_id = ArrayHelper::getValue($data, 'company_id');
		$loginEntity->status = StatusEnum::ENABLE;
		$this->repository->insert($loginEntity);
		return $loginEntity;
	}
	
	public function oneById($id, Query $query = null) {
		try {
			$loginEntity = parent::oneById($id, $query);
		} catch(NotFoundHttpException $e) {
			if($this->domain->oauth->isEnabled()) {
				$loginEntity = \App::$domain->account->oauth->oneById($id);
			} else {
				throw $e;
			}
		}
		return $loginEntity;
	}
	
	public function isExistsByLogin($login) {
		return $this->repository->isExistsByLogin($login);
	}
	
	/**
	 * @param $login
	 *
	 * @return \yii2module\account\domain\v3\entities\LoginEntity
	 *
	 * @throws NotFoundHttpException
	 */
	public function oneByLogin($login, Query $query = null) : LoginEntity {
		return $this->repository->oneByLogin($login, $query);
	}

    public function oneByPersonId(int $personId, Query $query = null) : LoginEntity {
        $query = Query::forge($query);
        $query->andWhere(['person_id' => $personId]);
        return $this->repository->one($query);
    }

	public function isValidLogin($login) {
		return $this->getLoginValidator()->isValid($login);
	}
	
	public function normalizeLogin($login) {
		return $this->getLoginValidator()->normalize($login);
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
	private function getLoginValidator() {
		$this->loginValidator = InstanceHelper::ensure($this->loginValidator, [], LoginValidatorInterface::class);
		return $this->loginValidator;
	}
	
}
