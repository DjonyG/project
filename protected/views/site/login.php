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
        <?php $this->renderPartial('//_forms/login', array('model'=>$model)); ?>
    </div>


