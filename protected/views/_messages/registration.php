<?php
/**
 * @var string $password
 * @var string $email
 */
?>
Thank you!<br>
You have been registered to our site <a href="<?php echo Yii::app()->createAbsoluteUrl('/'); ?>">Знакомства</a>.
<br>
Login: <?php echo Html::encode($email); ?><br>
Your password: <?php echo Html::encode($password); ?><br>
