<?php
/**
 * @var ActivationCode $code
 */
?>
<?php echo Yii::t('main', 'To switch off security lock, please use the following link:'); ?><br>
<a href="<?php echo $url = Yii::app()->createAbsoluteUrl('user/securityoff', array('code'=>$code->id)); ?>">
    <?php echo $url; ?>
</a>