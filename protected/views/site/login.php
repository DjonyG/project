<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form TbActiveForm */
?>

<h3>
    <span class="glyphicon glyphicon-log-in"></span>
    Вход на сайт
</h3>

    <div class="panel-body">
        <?php $form = $this->beginWidget('TbActiveForm', [
            'id' => 'login-form',
            'enableAjaxValidation' => false,
            'type' => 'horizontal',
            'htmlOptions' => ['class' => 'col-sm-6'],
            'showErrors' => false,
        ]); ?>

        <?php echo $form->errorSummary($model); ?>

        <?php echo $form->textFieldGroup($model, 'email'); ?>
        <?php echo $form->passwordFieldGroup($model, 'password'); ?>
        <?php echo $form->checkboxGroup($model, 'rememberMe'); ?>

        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <?php $this->widget(
                    'booster.widgets.TbButton',
                    ['buttonType' => TbButton::BUTTON_SUBMIT, 'context' => 'primary', 'label' => 'Войти']); ?>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>


