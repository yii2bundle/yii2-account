<?php

namespace yii2bundle\account\console\forms;

use Yii;
use yii2bundle\account\domain\v3\helpers\LoginHelper;
use yii\base\Model;
class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password',], 'trim'],
            [['login', 'password',], 'required'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login'         => Yii::t('main', 'login'),
            'rememberMe'        => Yii::t('main', 'remember_me'),
            'password'        => Yii::t('main', 'password'),
        ];
    }

    public function normalizeLogin($attribute)
    {
        $this->$attribute = LoginHelper::getPhone($this->$attribute);
    }
    
}
