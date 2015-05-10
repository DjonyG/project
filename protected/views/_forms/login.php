<?php

/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form TbActiveForm */

$form = $this->beginWidget('TbActiveForm', [
    'id' => 'login-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => ['class' => 'col-sm-5'],
    'showErrors' => false,
]); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldGroup($model, 'email'); ?>
<?php echo $form->passwordFieldGroup($model, 'password'); ?>
<?php echo $form->checkboxGroup($model, 'rememberMe'); ?>


<?php $this->widget(
    'booster.widgets.TbButton',
    ['buttonType' => TbButton::BUTTON_SUBMIT, 'context' => 'primary', 'label' => 'Войти']); ?>
<?php $this->endWidget(); ?>