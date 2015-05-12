<?php

/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form TbActiveForm */

$form = $this->beginWidget('TbActiveForm', [
    'id' => 'login-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => ['style' => 'width: 100%'],
    'showErrors' => false,
]); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldGroup($model, 'email', [
    'labelOptions' => ['label' => false],
    'prepend' => '<i class="fa fa-user"></i>',

]); ?>
<?php echo $form->passwordFieldGroup($model, 'password', [
    'labelOptions' => ['label' => false],
    'prepend' => '<i class="fa fa-lock"></i>'

]); ?>
<?php echo $form->checkboxGroup($model, 'rememberMe'); ?>


<?php $this->widget(
    'booster.widgets.TbButton', [
    'buttonType' => TbButton::BUTTON_SUBMIT,
    'context' => 'primary',
    'label' => 'Войти',
    'icon' => 'ok',
    'htmlOptions' => ['class' => 'btn-block', 'style' => 'font-size: 16px;']]); ?>
<?php $this->endWidget(); ?>