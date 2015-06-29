<?php
/* @var $this SiteController */
/* @var $model Profile */
/* @var $form TbActiveForm */
?>

<div style="max-width: 700px; margin: 0 auto; ">
    <h2 style="text-align: left;  margin-bottom: 10px">
        <span class="glyphicon glyphicon-pencil"></span>
        Создание профиля
    </h2>
    <div class="panel-body">
        <?php $form = $this->beginWidget('TbActiveForm', [
            'id' => 'profile-form',
            'type' => 'horizontal',
            'enableAjaxValidation' => false,
            'showErrors' => false,
        ]); ?>

        <?php echo $form->errorSummary($model); ?>

        <?php echo $form->textFieldGroup($model, 'user_name', [
            'wrapperHtmlOptions' => [
                'class' => 'col-sm-5',
            ],
            'labelOptions' => ['label' => 'Ваше имя'],
        ]); ?>

        <?php echo $form->dropDownListGroup($model, 'floor', [
            'wrapperHtmlOptions' => [
                'class' => 'col-sm-5',
            ],
            'widgetOptions' => [
                'data' => Profile::$floorStatues,
            ],
            'labelOptions' => ['label' => 'Пол'],
        ]); ?>

        <?php echo $form->datePickerGroup(
            $model,
            'date_born',
            [
                'widgetOptions' => [
                    'options' => [
                        'language' => 'ru',
                        'format' => 'dd.mm.yyyy',
                        'maxDate' => date('Y-m-d', time()),
                        'autoclose' => true,
                    ],
                ],
                'wrapperHtmlOptions' => [
                    'class' => 'col-sm-5',
                ],
                'labelOptions' => ['label' => 'День рождения'],
            ]
        ); ?>


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
            'label' => 'Создать профиль',
            'htmlOptions' => ['class' => 'btn-block', 'style' => 'font-size: 18px;']]); ?>

        <?php $this->endWidget(); ?>


    </div>
</div>