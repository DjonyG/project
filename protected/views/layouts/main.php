<?php /* @var $this Controller */ ?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="en">
    <base href="<?php echo Yii::app()->createAbsoluteUrl('/'); ?>/">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
    <?php Yii::app()->clientScript->registerCoreScript('cookie'); ?>

	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->

    <!-- FANCYBOX -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->assetManager->getAssetUrl('ext/fancybox/jquery.fancybox.css'); ?>" />
    <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->getAssetUrl('ext/fancybox/jquery.fancybox.pack.js')); ?>

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">

    <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->getAssetUrl('ext/ace/assets/js/ace-extra.min.js')); ?>
    <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->getAssetUrl('ext/ace/assets/js/date-time/moment.min.js')); ?>
    <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->getAssetUrl('ext/ace/assets/js/date-time/daterangepicker.min.js')); ?>
    <?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->assetManager->getAssetUrl('ext/ace/assets/js/chosen.jquery.min.js')); ?>

    <script src="<?= Yii::app()->request->baseUrl;?>/js/main.js"></script>

</head>

<body>

<div class="container" id="page" style="padding: 0">
            <?php
            $this->widget(
                'booster.widgets.TbNavbar',
                array(
                    'type' => 'inverse',
                    'brand' => CHtml::tag('div', ['class' => 'logo-head'], CHtml::encode(Yii::app()->name)),
                    'brandUrl' => '#',
                    'brandOptions' => array('style' => 'margin-left: 10px;'),
                    'htmlOptions' => array('style' => 'border-radius: 0;'),
                    'collapse' => true, // requires bootstrap-responsive.css
                    'fixed' => false,
                    'fluid' => true,
                    'items' => array(
//                        array(
//                            'class' => 'booster.widgets.TbMenu',
//                            'type' => 'navbar',
//                            'items' => array(
//                                array('label' => 'Home', 'url' => '#'),
//                                array('label' => 'Link', 'url' => '#'),
//                            ),
//                        ),
                        array(
                            'class' => 'booster.widgets.TbMenu',
                            'type' => 'navbar',
                            'htmlOptions' => array('class' => 'pull-right'),
                            'items' => array(
                                ['label'=>'Вход', 'url'=> ['/site/login'], 'visible'=>Yii::app()->user->isGuest, 'linkOptions'=> ['class'=>'fancybox.ajax various'], 'active' => true],
                            ),
                        ),
                    ),
                )
            );
            ?>

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

    <div id="footer">
        <div style="display: inline-block" class="bottom-menu">
            <?php $this->widget('TbMenu',array(
                'type'=>'pills',
                'htmlOptions'=>array(
                    'style'=>'margin-bottom:0',
                ),
                'items'=>array(
                    array('label'=>'Home', 'url'=>array('/site/index')),
                    array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
                    array('label'=>'Contact', 'url'=>array('/site/contact')),
                    array('label' => 'Вход', 'url' => array('/site/login')),
                    array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
                ),
            )); ?>

        </div>
        <div class="muted">
            <small>Copyright © <?php echo date('Y'); ?> <?=$_SERVER["SERVER_NAME"];?> All rights reserved.</small><br/>
            <div class="logo-footer">
<!--                --><?php //echo Html::staticImage('images/design/logo_normal_100.png'); ?>
            </div>
        </div>
    </div>

</div><!-- page -->

</body>
</html>
