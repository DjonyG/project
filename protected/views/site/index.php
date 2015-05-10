<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<?php

if (Yii::app()->user->isGuest){

$this->widget(
    'booster.widgets.TbCarousel',
    array(
        'items' => array(
            array(
                'image' => 'images/first-placeholder1100x530.gif',
                'label' => 'First Thumbnail label',
                'caption' => 'First Caption.'
            ),
            array(
                'image' => 'images/second-placeholder1100x530.gif',
                'label' => 'Second Thumbnail label',
                'caption' => 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.'
            ),
            array(
                'image' => 'images/third-placeholder1100x530.gif',
                'label' => 'Third Thumbnail label',
                'caption' => 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.'
            ),
        ),
    )
);
}
else{


}
?>


