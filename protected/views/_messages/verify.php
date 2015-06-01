<?php
/**
 * @var ActivationCode $code
 */
?>
<?php echo Yii::t('main', 'To verify your email, please use the following link:'); ?><br>
<a href="<?php echo $url = Yii::app()->createAbsoluteUrl('site/verify', array('code'=>$code->id)); ?>">
    <?php echo $url; ?>
</a>