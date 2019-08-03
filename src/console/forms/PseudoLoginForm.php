<?php

namespace yii2bundle\account\console\forms;

use common\helpers\RBACRoles;
use domain\v1\account\models\Subjects;
use domain\v1\account\models\TypeSubject;
use domain\v1\account\models\User;
use Yii;
use yii\base\Model;

class PseudoLoginForm extends Model
{
	public $login;
	public $email;
	public $parentLogin;

    /**
     * @inheritdoc
     */
    public function rules()
    {
	    return [
		    [['login', 'email', 'parentLogin'], 'trim'],
		    [['login', 'email'], 'required'],
	    ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
	    return [
		    'login' => Yii::t('main', 'login'),
	    ];
    }
 
 
}
