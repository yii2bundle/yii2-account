<?php
/**
 * @var $this yii\web\View
 * @var $model yii\base\Model
 */

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii2rails\extension\menu\helpers\MenuHelper;

$this->title = Yii::t('account/security', 'email');
\App::$domain->navigation->breadcrumbs->create($this->title);

?>

<?= Tabs::widget([
	'items' => MenuHelper::gen('yii2bundle\account\module\helpers\SecurityMenu'),
]) ?>

<br/>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'email')->textInput(['placeholder'=>$model->email]) ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('action', 'update'), ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
