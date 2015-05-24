<?php /* @var $this Controller */ ?>
<?php /* @var $content string */ ?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="en">
    <base href="<?php echo Yii::app()->createUrl('/'); ?>/">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
    <?php Yii::app()->clientScript->registerCoreScript('cookie'); ?>

    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css"
          media="screen, projection">
    <![endif]-->

    <!-- FANCYBOX -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->assetManager->getAssetUrl('ext/fancybox/jquery.fancybox.css'); ?>" />
    <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->getAssetUrl('ext/fancybox/jquery.fancybox.pack.js')); ?>


    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->assetManager->getAssetUrl('ext/ace/assets/css/daterangepicker.css'); ?>" />

    <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->getAssetUrl('ext/ace/assets/js/ace-extra.min.js')); ?>
    <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->getAssetUrl('ext/ace/assets/js/date-time/moment.min.js')); ?>
    <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->getAssetUrl('ext/ace/assets/js/date-time/daterangepicker.min.js')); ?>
    <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->getAssetUrl('ext/ace/assets/js/chosen.jquery.min.js')); ?>

    <script src="<?= Yii::app()->request->baseUrl; ?>/js/main.js"></script>

</head>

<body>

<div class="container" id="page" style="padding: 0">
    <?php
    $this->widget(
        'booster.widgets.TbNavbar',
        [
            'type' => 'inverse',
            'brand' => CHtml::tag('div', ['class' => 'logo-head'], CHtml::encode(Yii::app()->name)),
            'brandUrl' => '#',
            'brandOptions' => ['style' => 'margin-left: 10px;'],
            'htmlOptions' => ['style' => 'border-radius: 0;'],
            'collapse' => true, // requires bootstrap-responsive.css
            'fixed' => false,
            'fluid' => true,
            'items' => [
                [
                    'class' => 'booster.widgets.TbMenu',
                    'type' => 'navbar',
                    'htmlOptions' => ['class' => 'pull-right'],
                    'items' => [
                        ['label' => 'Вход', 'url' => ['/site/login'], 'visible' => Yii::app()->user->isGuest, 'linkOptions' => ['class' => 'fancybox.ajax various'], 'active' => true],
                    ],
                ],
            ],
        ]
    );
    ?>

    <?php if (isset($this->breadcrumbs)): ?>
        <?php $this->widget('zii.widgets.CBreadcrumbs', [
            'links' => $this->breadcrumbs,
        ]); ?><!-- breadcrumbs -->
    <?php endif ?>

    <?php echo $content; ?>

    <div class="clear"></div>

    <div id="footer">
        <div style="display: inline-block" class="bottom-menu">
            <?php $this->widget('TbMenu', [
                'type' => 'pills',
                'htmlOptions' => [
                    'style' => 'margin-bottom:0',
                ],
                'items' => [
                    ['label' => 'Home', 'url' => ['/site/index']],
                    ['label' => 'About', 'url' => ['/site/page', 'view' => 'about']],
                    ['label' => 'Contact', 'url' => ['/site/contact']],
                    ['label' => 'Вход', 'url' => ['/site/login']],
                    ['label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => ['/site/logout'], 'visible' => !Yii::app()->user->isGuest],
                ],
            ]); ?>

        </div>
        <div class="muted">
            <small>Copyright © <?php echo date('Y'); ?> <?= $_SERVER["SERVER_NAME"]; ?> All rights reserved.</small>
            <br/>

            <div class="logo-footer">
                <!--                --><?php //echo Html::staticImage('images/design/logo_normal_100.png'); ?>
            </div>
        </div>
    </div>

</div>
<!-- page -->

</body>
</html>
