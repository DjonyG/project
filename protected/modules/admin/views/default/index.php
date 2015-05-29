<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);
?>
<?php
$this->beginWidget(
    'booster.widgets.TbPanel',
    [
        'title'       => 'Home admin page',
        'headerIcon'  => 'home',
        'content'     => '',
        'htmlOptions' => ['style' => 'margin-top: 20px; display: inline-block; width:100%;']
    ]
);
?>

<?php $this->endWidget(); ?>