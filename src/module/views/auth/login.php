<?php

/* @var $this yii\web\View */
/* @var $model LoginForm */

use yii\helpers\Html;
use yii2rails\app\domain\helpers\EnvService;
use yii2module\account\domain\v2\forms\LoginForm;

$this->title = Yii::t('account/auth', 'login_title');
//\App::$domain->navigation->breadcrumbs->create($this->title);

$loginForm = $this->render('helpers/_loginForm.php', [
	'model' => $model,
]);

$items = [];
$items[] = [
	'label' => Yii::t('account/auth', 'title'),
	'content' => $loginForm,
];

if(\App::$domain->account->oauth->isEnabled()) {
	$items[] = [
		'label' => Yii::t('account/oauth', 'title'),
		'content' => $this->render('helpers/_loginOauth.php'),
	];
}

if(count($items) > 1) {
    $html = \yii\bootstrap\Tabs::widget([
	    'items' => $items,
    ]);
} else {
	$html = $loginForm;
}

?>

<?php if(APP == BACKEND) { ?>

	<div class="login-box">
		<div class="login-logo">
			<?= Html::encode($this->title) ?>
		</div>
		<div class="login-box-body">
			<?= $loginForm ?>
			<?= Html::a(Yii::t('main', 'go_to_frontend'), EnvService::getUrl(FRONTEND)) ?>
		</div>
	</div>

<?php } else { ?>

	<div class="user-login">
		<h1>
			<?= Html::encode($this->title) ?>
		</h1>
		<div class="row">
			<div class="col-lg-5">
                <?= $html ?>
			</div>
		</div>
	</div>

<?php } ?>
