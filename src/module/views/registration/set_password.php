<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model yii\base\Model */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('account/registration', 'set_password_title');
\App::$domain->navigation->breadcrumbs->create(['account/registration', 'title']);
\App::$domain->navigation->breadcrumbs->create($this->title);
?>
<div class="user-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('account/registration', 'set_password_text') ?></p>

    <div class="row">
        <div class="col-lg-5">
			<?php $form = ActiveForm::begin(['id' => 'form-create']); ?>

            <div class="form-group field-setsecurityform-login required">
                <label class="control-label" for="setsecurityform-login"><?= Yii::t('account/main', 'login') ?></label>
	            <div><?= $login ?></div>
            </div>
            
            <?= $form->field($model, 'password')->passwordInput() ?>
			
			<?= $form->field($model, 'password_repeat')->passwordInput() ?>
	
	        <?= $form->field($model, 'email') ?>
	
	        <?= $form->field($model, 'email_repeat') ?>

            <div class="form-group">
	            <?= Html::submitButton(Yii::t('action', 'next'), [
		            'class' => 'btn btn-primary',
		            'name' => 'create-button',
	            ]) ?>
            </div>
			
			<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
