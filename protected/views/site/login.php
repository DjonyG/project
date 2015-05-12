<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form TbActiveForm */
?>


<div style="max-width: 350px; margin: 0 auto; ">
    <h3 style="text-align: center; margin-bottom: 0">
        <span class="glyphicon glyphicon-log-in"></span>
        Вход на сайт
    </h3>
    <div class="panel-body">
        <?php $this->renderPartial('//_forms/login', array('model'=>$model)); ?>
    </div>
</div>


