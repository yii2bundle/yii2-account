<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model yii\base\Model */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii2rails\app\domain\helpers\EnvService;
use yii2lab\rest\domain\helpers\ApiVersionConfig;

$this->title = Yii::t('account/registration', 'create_title');
//\App::$domain->navigation->breadcrumbs->create(['account/registration', 'title']);
\App::$domain->navigation->breadcrumbs->create($this->title);
?>
<div class="user-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('account/auth', 'signup_text') ?></p>

    <script>
		function sendSms() {
			var form = $('form#form-signup');
			var data = {};
			data.login = form.find('#registrationform-login').val();
			data.email = form.find('#registrationform-email').val();
			data.activation_code = form.find('#registrationform-activation_code').val();
			$.ajax({
				method: 'post',
				url: '<?= $_ENV['API_DOMAIN_URL'] . SL . ApiVersionConfig::defaultApiVersionSting() .'/registration/create-account' ?>',
				dataType: 'json',
				data: data,
				success: function () {
					alert('<?= Yii::t('account/registration', 'sms_with_code_sended') ?>');
				},
				error: function (jqXHR) {
					var message = '';
					for (var k in jqXHR.responseJSON) {
						message = message + "\n" + jqXHR.responseJSON[k].message;
					}
					alert(message);
				}
			});
			return false;
		}
    </script>

    <div class="row">
        <div class="col-lg-5">
			<?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
			
			<?php $buttonGetCode = Html::submitButton(Yii::t('account/registration', 'send_activation_code'), [
				'onclick' => 'return sendSms()',
				'class' => 'btn btn-default btn-block',
			]); ?>
			
			<?= $form->field($model, 'login', [
				'template' => "
                     <div class=\"row\">
                        <div class=\"col-xs-12\">
                           {label}
                        </div>
                        <div class=\"col-xs-8\">
                            {input}
                            {error}
                        </div>
                        <div class=\"col-xs-4\">
                            $buttonGetCode
                        </div>
                        {hint}
                     </div>",
			]) ?>
			
			<?= $form->field($model, 'activation_code') ?>

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
