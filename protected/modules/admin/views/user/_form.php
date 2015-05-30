<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form TbActiveForm */
?>

<?php $form = $this->beginWidget('TbActiveForm', [
    'id' => 'user-form',
    'enableAjaxValidation' => false,
    'type' => 'horizontal',
    'htmlOptions' => ['class' => 'well col-sm-5']
]); ?>


<?php echo $form->errorSummary($model); ?>

<?php if (Yii::app()->user->checkAccess('administrator')): ?>

    <?php echo $form->textFieldGroup($model, 'email'); ?>

    <?php echo $form->dropDownListGroup($model, 'email_is_verified', [
        'wrapperHtmlOptions' => ['class' => 'col-lg-3'],
        'widgetOptions' => [
            'data' => [0 => 'No', 1 => 'Yes'],
        ],
    ]); ?>

    <?php echo $form->textFieldGroup($model, 'new_password', [
        'size' => 14,
        'maxlength' => 40,
    ]); ?>

    <?php echo $form->dropDownListGroup($model, 'banned', [
        'wrapperHtmlOptions' => ['class' => 'col-lg-3'],
        'widgetOptions' => [
            'data' => User::$banTypes,
        ],
    ]); ?>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?php $this->widget('booster.widgets.TbButton', [
                'buttonType' => 'submit',
                'context' => 'primary',
                'label' => $model->isNewRecord ? 'Create' : 'Update'
            ]); ?>
        </div>
    </div>

<?php endif; ?>

<?php $this->endWidget(); ?>