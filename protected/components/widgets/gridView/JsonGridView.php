<?php
/**
 * Created by PhpStorm.
 * User: Evgeny
 * Date: 15.12.2014
 * Time: 15:07
 */

class JsonGridView extends TbJsonGridView
{
    public $enablePageSizeSelector = false;


    public function init()
    {
            if ($this->enablePageSizeSelector && $this->dataProvider->pagination != false) {
                $key = $this->id . 'pageSize';
                if ($pageSize = Yii::app()->request->getParam('pageSize')) {
                    Yii::app()->user->setParam($key, $pageSize);
                    $this->dataProvider->getPagination()->pageSize = $pageSize;
                } else {
                    if ($pageSize = Yii::app()->user->getParam($key, 25)) {
                        $this->dataProvider->getPagination()->pageSize = $pageSize > 2000 ? 2000 : $pageSize;
                    }
                }
            }

        parent::init();
    }

    public function renderSummary()
    {
        if (!$this->json) {
            if ($this->enablePageSizeSelector) {
                $data = is_array($this->enablePageSizeSelector) ? $this->enablePageSizeSelector : array(
                    '10' => 10,
                    '25' => 25,
                    '50' => 50,
                    '100' => 100,
                    '500' => 500,
                    '1000' => 1000,
                );

                echo '<div style="float: right;">';
                echo 'Page size';
                echo '&nbsp;';
                echo CHtml::dropDownList(
                    'pageSize',
                    $this->dataProvider->getPagination()->pageSize,
                    $data,
                    [
                        'onchange' => "jQuery.fn.yiiJsonGridView.update('{$this->id}', {
                                url: jQuery.param.querystring(jQuery.fn.yiiJsonGridView.getUrl('{$this->id}'), {pageSize: jQuery(this).val()})});",
                        'style' => "width:70px; border-radius: 4px; padding: 4px 6px; border: 1px solid #CCC;",
                    ]
                );
                echo '</div>';

            }
        }
    }





}


