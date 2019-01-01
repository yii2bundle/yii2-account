<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('account/restore-password', 'title');
\App::$domain->navigation->breadcrumbs->create($this->title);
?>
<div class="user-reset-password">
	<h1><?= Html::encode($this->title) ?></h1>

	<p><?= Yii::t('account/restore-password', 'enter_new_password') ?></p>

	<div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(); ?>

				<?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
			
			    <?= $form->field($model, 'new_password_repeat')->passwordInput() ?>

				<div class="form-group">
					<?= Html::submitButton(Yii::t('account/restore-password', 'save_password_action'), ['class' => 'btn btn-primary']) ?>
				</div>

			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
