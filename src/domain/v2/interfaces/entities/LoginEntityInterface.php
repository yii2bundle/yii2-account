<?php

namespace yii2module\account\domain\v2\interfaces\entities;

use yii\web\IdentityInterface;
use yii\web\NotFoundHttpException;
use yii2lab\domain\interfaces\repositories\CrudInterface;
use yii2module\account\domain\v2\entities\LoginEntity;

/**
 * Interface LoginEntityInterface
 *
 * @package yii2module\account\domain\v2\interfaces\entities
 *
 * @property integer          $id
 * @property string           $login
 * @property integer          $status
 * @property string           $token
 * @property array            $roles
 * @property string           $username
 * @property string           $created_at
 * @property SecurityEntity   $security
 */
interface LoginEntityInterface extends IdentityInterface {
	


}