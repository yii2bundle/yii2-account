<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \yii2module\account\module\forms\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>

<br/>

<p class="login-box-msg"><?= Yii::t('account/auth', 'login_text') ?></p>

<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'login') ?>

	<?= $form->field($model, 'password')->passwordInput() ?>
	
	<?=$form->field($model, 'rememberMe', [
		'checkboxTemplate'=>'<div class="checkbox">{beginLabel}{input}{labelTitle}{endLabel}{error}{hint}</div>',
	])->checkbox();?>
	
	<div class="form-group">
		<?=Html::submitButton(Yii::t('account/auth', 'login_action'), ['class' => 'btn btn-primary btn-flat', 'name' => 'login-button']) ?>
	</div>
	
<?php ActiveForm::end(); ?>

<?= Html::a(Yii::t('account/auth', 'register_new_user'), ['/user/registration']) ?>
    <br/>
<?= Html::a(Yii::t('account/auth', 'i_forgot_my_password'), ['/user/restore-password']) ?>