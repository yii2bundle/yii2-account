<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

use yii\authclient\widgets\AuthChoice;

?>

<br/>

<p class="login-box-msg"><?= Yii::t('account/oauth', 'login_text') ?></p>

<?= AuthChoice::widget([
	'baseAuthUrl' => ['/user/oauth/login'],
]) ?>
