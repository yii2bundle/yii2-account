<?php
/**
 * @var $this yii\web\View
 * @var $model yii\base\Model
 */

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii2rails\extension\menu\helpers\MenuHelper;

$this->title = Yii::t('account/security', 'password');
\App::$domain->navigation->breadcrumbs->create($this->title);

?>

<?= Tabs::widget([
	'items' => MenuHelper::gen('yii2bundle\account\module\helpers\SecurityMenu'),
]) ?>

<br/>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'new_password')->passwordInput() ?>

<?= $form->field($model, 'new_password_repeat')->passwordInput() ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<div class="form-group">
	<?= Html::submitButton(Yii::t('action', 'update'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
