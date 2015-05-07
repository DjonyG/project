<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form TbActiveForm */
?>

<h3>
    <span class="glyphicon glyphicon-log-in"></span>
    User Login
</h3>

    <div class="panel-body">
        <?php $form = $this->beginWidget('TbActiveForm', [
            'id' => 'sign-in-form',
            'enableAjaxValidation' => false,
            'type' => 'horizontal',
            'htmlOptions' => ['class' => 'col-sm-6'],
            'showErrors' => false,
        ]); ?>

        <?php echo $form->errorSummary($model); ?>

        <?php echo $form->textFieldGroup($model, 'username'); ?>

        <?php echo $form->passwordFieldGroup($model, 'password'); ?>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <?php echo $form->checkBox($model, 'rememberMe'); ?>
                <?php echo $form->label($model, 'rememberMe'); ?>

                <br>
                <?php $this->widget(
                    'booster.widgets.TbButton',
                    ['buttonType' => TbButton::BUTTON_SUBMIT, 'context' => 'primary', 'label' => 'Login']); ?>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>


