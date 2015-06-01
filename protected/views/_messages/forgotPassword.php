<?php
/**
 * @var ActivationCode $code
 */
?>
<?php echo Yii::t('main', 'Forgotten code'); ?>: <b><?php echo $code->id; ?></b><br>
<?php echo Yii::t('main', 'To recover a lost password, please use this link'); ?><br>
<a href="<?php echo $url = Yii::app()->createAbsoluteUrl('site/resetPassword', array('code' => $code->id)); ?>">
    <?php echo $url; ?>
</a>
