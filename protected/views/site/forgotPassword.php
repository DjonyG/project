<?php
/* @var $this SiteController */
/* @var $model ForgotPasswordForm */
/* @var $form TbActiveForm */
?>
<div style="max-width: 400px; margin: 0 auto; ">
    <h3 style="text-align: center; margin-bottom: 0">
        <span class="glyphicon glyphicon-exclamation-sign"></span>
        Восстановление Пароля
    </h3>

    <div class="panel-body">

    <?php
    /** @var TbActiveForm $form */
    $form = $this->beginWidget('TbActiveForm', [
        'id' => 'forgot-password-form-forgotPassword-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => ['style' => 'width: 100%; font-size: 16px;'],
        'showErrors' => false,
    ]); ?>

        <?php echo $form->errorSummary($model); ?>

        <?php echo $form->textFieldGroup($model, 'email', [
            'labelOptions' => ['label' => false],
            'prepend' => '<i class="fa fa-user"></i>',

        ]); ?>


    <?php $this->widget(
        'booster.widgets.TbButton', [
        'buttonType' => TbButton::BUTTON_SUBMIT,
        'context' => 'primary',
        'label' => 'Отправить',
        'htmlOptions' => ['class' => 'btn-block', 'style' => 'font-size: 18px;']]); ?>

            <?php $this->endWidget(); ?>


    </div>

</div><!-- form -->