<?php
/**
 * @var $this Controller
 * @var $content string
 */
?>
<?php $this->beginContent('/layouts/main'); ?>

<?php
if (isset($this->breadcrumbs)) {
    $this->widget('booster.widgets.TbBreadcrumbs', [
        'links' => $this->breadcrumbs,
        'homeLink' => CHtml::link('Home', Yii::app()->createUrl('/admin/default/index')),
        'htmlOptions' => ['class' => 'breadcrumb', 'style' => 'margin-bottom: 2px;']
    ]);
}
?>


<?php
if (count($this->menu) > 0) {
    $this->widget(
        'booster.widgets.TbNavbar',
        [
            'type' => 'inverse',
            'brand' => 'Operations',
            'brandOptions' => ['style' => 'color: white;'],
            'fixed' => 'bottom',
            'items' => [
                [
                    'class' => 'booster.widgets.TbMenu',
                    'type' => 'navbar',
                    'items' => $this->menu
                ]
            ],
            'htmlOptions' => []
        ]
    );
}
?>
<?php $this->widget('application.components.widgets.userMessage.UserMessageWidget'); ?>
<?php echo $content; ?>

<?php $this->endContent(); ?>

