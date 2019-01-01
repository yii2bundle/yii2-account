<?php

namespace yii2module\account\domain\v1\repositories\ar;

use Yii;
use yii\web\UnauthorizedHttpException;
use yii2lab\domain\exceptions\UnprocessableEntityHttpException;
use yii2lab\domain\helpers\ErrorCollection;
use yii2lab\domain\repositories\BaseRepository;
use yii2module\account\domain\v1\interfaces\repositories\SecurityInterface;
use yii2module\account\domain\v1\models\User;

class SecurityRepository extends BaseRepository implements SecurityInterface {
	
	public function changePassword($password, $newPassword) {
        $userModel = $this->getUserModel($password);
        if(strlen($newPassword) < 8) {
            $error = new ErrorCollection();
            $error->add('new_password', 'account/auth', 'weak_password');
            throw new UnprocessableEntityHttpException($error);
        }
        $userModel->password_hash = Yii::$app->security->generatePasswordHash($newPassword);
		$userModel->save();
	}
	
	public function changeEmail($password, $email) {
        $userModel = $this->getUserModel($password);
        $userModel->email = $email;
        $userModel->save();
	}

	private function getUserModel($password) {
        $userId = Yii::$app->user->identity->getId();
	    $userModel = User::find()->where(['id' => $userId])->one();
        if(empty($userModel) || empty($userModel->id)) {
            throw new UnauthorizedHttpException();
        }
        if(!Yii::$app->security->validatePassword($password, $userModel->password_hash)) {
            $error = new ErrorCollection();
            $error->add('password', 'account/auth', 'incorrect_password');
            throw new UnprocessableEntityHttpException($error);
        }
        return $userModel;
    }
}