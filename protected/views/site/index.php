<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>
<?php

if (Yii::app()->user->isGuest) {

    $this->widget(
        'booster.widgets.TbCarousel',
        [
            'items' => [
                [
                    'image' => 'images/first-placeholder1100x530.gif',
                    'label' => 'First Thumbnail label',
                    'caption' => 'First Caption.'
                ],
                [
                    'image' => 'images/second-placeholder1100x530.gif',
                    'label' => 'Second Thumbnail label',
                    'caption' => 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.'
                ],
                [
                    'image' => 'images/third-placeholder1100x530.gif',
                    'label' => 'Third Thumbnail label',
                    'caption' => 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.'
                ],
            ],
        ]
    );

    $this->widget(
        'booster.widgets.TbButton', [
        'buttonType' => TbButton::BUTTON_BUTTON,
        'context' => 'info',
        'size' => 'large',
        'label' => 'Регистрация',
        'htmlOptions' => ['class' => 'btn-block',
            'style' => 'font-size: 22px; margin: 25px auto 0 auto; max-width: 250px;']]);

} else {
    $this->beginWidget(
        'booster.widgets.TbJumbotron',
        [
            'heading' => 'Добро пожаловать!',
        ]
    );

}
?>


