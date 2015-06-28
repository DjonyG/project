<?php
/* @var $this SiteController */
/* @var $model Profile */
/* @var $form TbActiveForm */
?>

<div style="max-width: 600px; margin: 0 auto; ">
    <h3 style="text-align: center;  margin-bottom: 0">
        <span class="glyphicon glyphicon-pencil"></span>
        Создание профиля
    </h3>
    <div class="panel-body">
        <?php $form = $this->beginWidget('TbActiveForm', [
            'id' => 'profile-form',
            'enableAjaxValidation' => false,
            'htmlOptions' => ['style' => 'width: 100%; font-size: 16px;'],
            'showErrors' => false,
        ]); ?>

        <?php echo $form->errorSummary($model); ?>

        <?php echo $form->textFieldGroup($model, 'user_name', [
            'labelOptions' => ['label' => 'Ваше имя'],
        ]); ?>

        <?php echo $form->textFieldGroup($model, 'city', [
            'labelOptions' => ['label' => 'Город'],
        ]); ?>

<!--        --><?php //$model->acceptTerms = false; ?>
<!--        --><?php //echo $form->checkboxGroup($model, 'acceptTerms', [
//            'widgetOptions' => [
//                'htmlOptions' => ['checked' => 'checked'],
//            ],
//        ]); ?>

        <?php $this->widget(
            'booster.widgets.TbButton', [
            'buttonType' => TbButton::BUTTON_SUBMIT,
            'context' => 'primary',
            'label' => 'Создать профиль',
            'htmlOptions' => ['class' => 'btn-block', 'style' => 'font-size: 18px;']]); ?>

        <?php $this->endWidget(); ?>


    </div>
</div>