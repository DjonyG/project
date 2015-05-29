<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_created'); ?>
		<?php echo $form->textField($model,'date_created'); ?>
		<?php echo $form->error($model,'date_created'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_last'); ?>
		<?php echo $form->textField($model,'date_last'); ?>
		<?php echo $form->error($model,'date_last'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ip_create'); ?>
		<?php echo $form->textField($model,'ip_create',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'ip_create'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ip_last'); ?>
		<?php echo $form->textField($model,'ip_last',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'ip_last'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'role'); ?>
		<?php echo $form->textField($model,'role',array('size'=>13,'maxlength'=>13)); ?>
		<?php echo $form->error($model,'role'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email_is_verified'); ?>
		<?php echo $form->textField($model,'email_is_verified'); ?>
		<?php echo $form->error($model,'email_is_verified'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'banned'); ?>
		<?php echo $form->textField($model,'banned'); ?>
		<?php echo $form->error($model,'banned'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'security_lock'); ?>
		<?php echo $form->textField($model,'security_lock'); ?>
		<?php echo $form->error($model,'security_lock'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->