<?php
/* @var $this SiteController */
/* @var $model RegisterForm */
/* @var $form TbActiveForm */
?>

<?php $form = $this->beginWidget('TbActiveForm', [
    'id' => 'sign-up-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => ['style' => 'width: 100%; font-size: 16px;'],
    'showErrors' => false,
]); ?>

<?php echo $form->errorSummary($model); ?>

<?php echo $form->textFieldGroup($model, 'username', [
    'labelOptions' => ['label' => false],
    'prepend' => '<i class="fa fa-user"></i>',

]); ?>

<?php echo $form->passwordFieldGroup($model, 'password', [
    'labelOptions' => ['label' => false],
    'prepend' => '<i class="fa fa-lock"></i>'

]); ?>

<?php $model->acceptTerms = false; ?>
<?php echo $form->checkboxGroup($model, 'acceptTerms', [
    'widgetOptions' => [
        'htmlOptions' => ['checked' => 'checked'],
    ],
]); ?>

<?php $this->widget(
    'booster.widgets.TbButton', [
    'buttonType' => TbButton::BUTTON_SUBMIT,
    'context' => 'primary',
    'label' => 'Зарегистрироваться',
    'htmlOptions' => ['class' => 'btn-block', 'style' => 'font-size: 18px;']]); ?>

<?php $this->endWidget(); ?>



