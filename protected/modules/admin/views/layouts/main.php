<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <base href="<?php echo Yii::app()->createAbsoluteUrl('/'); ?>/">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>

    <link href="<?php echo Html::staticUrl('images/favicon.ico'); ?>" rel="shortcut icon" type="image/x-icon"/>

    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
    <?php Yii::app()->clientScript->registerCoreScript('cookie'); ?>

    <!-- FANCYBOX -->
    <link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->assetManager->getAssetUrl('ext/fancybox/jquery.fancybox.css'); ?>"/>
    <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->getAssetUrl('ext/fancybox/jquery.fancybox.pack.js')); ?>

    <link rel="stylesheet" type="text/css"
          href="<?php echo Yii::app()->assetManager->getAssetUrl('/css/admin.css'); ?>"/>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<?php /** @var $this Controller */ ?>
<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar">u</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo Yii::app()->createUrl('/admin/default/index'); ?>">Знакомства</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a><?php echo date('Y-m-d H:i:s'); ?></a></li>
                <li>
                    <a href="<?php echo Yii::app()->createUrl('/site/logout'); ?>"><?php echo (Yii::app()->user->isImpersonate() ? 'Return ' : 'Logout ') . '(' . Yii::app()->user->name . ')'; ?></a>
                </li>
            </ul>
        </div>
    </div>
</div>


<div class="container-fluid" id="page">
    <div class="row" style="margin-left: 5px; min-width: 800px;">
        <div class="col-md-2 sidebar">
            <?php $this->widget('booster.widgets.TbMenu', [
                'type' => 'list',
                'htmlOptions' => [
                    'class' => 'nav nav-sidebar',
                    'id' => 'admin-menu'
                ],
                'items' => [
                    ['label' => 'WebApp', 'url' => ['/', '#' => ''], 'icon' => 'home'],
                    ['label' => 'показать все', 'itemOptions' => ['class' => 'nav-stat toggle-menu', 'onclick' => '$(".nav-list .nav-header").click(); return false;'], 'url' => ['#']],
                    ['label' => 'Users', 'itemOptions' => ['class' => 'nav-header'], 'visible' => Yii::app()->user->checkAccess('administrator')],
                    ['label' => 'Users', 'url' => ['/admin/user/admin'], 'icon' => 'user', 'visible' => Yii::app()->user->checkAccess('administrator'), 'itemOptions' => ['class' => 'nav-stat', 'menu-id' => 1]],

                ]
            ]); ?>
        </div>

        <?php $this->widget('application.components.widgets.userMessage.UserMessageWidget'); ?>
        <div class="col-md-10 col-md-offset-2">
            <?php echo $content; ?>
        </div>
    </div>
</div>



</body>
</html>
