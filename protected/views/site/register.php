<?php
/* @var $this SiteController */
/* @var $model RegisterForm */
/* @var $form TbActiveForm */
?>

<div style="max-width: 400px; margin: 0 auto; ">
    <h3 style="text-align: center;  margin-bottom: 0">
        <span class="glyphicon glyphicon-pencil"></span>
        Регистрация
    </h3>
    <div class="panel-body">
        <?php $this->renderPartial('//_forms/register', ['model' => $model]); ?>
    </div>
</div>



