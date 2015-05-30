<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form TbActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('TbActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'type'   => 'horizontal',
        'htmlOptions' => ['class' => 'well col-sm-4']
    )); ?>

    <?php echo $form->textFieldGroup($model, 'id', array('size' => 10, 'maxlength' => 10)); ?>

    <?php echo $form->textFieldGroup($model, 'email', array('size' => 50, 'maxlength' => 50)); ?>

    <?php echo $form->textFieldGroup($model, 'date_created'); ?>

    <?php echo $form->textFieldGroup($model, 'date_last'); ?>

    <?php echo $form->textFieldGroup($model, 'ip_create', array('size' => 15, 'maxlength' => 15)); ?>

    <?php echo $form->textFieldGroup($model, 'role', array('size' => 14, 'maxlength' => 14)); ?>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?php $this->widget('booster.widgets.TbButton', [
                'buttonType' => 'submit',
                'label' => 'Search',
                'context' => 'primary'
            ]); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div>