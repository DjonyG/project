<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hett
 * Date: 13.12.12
 * Time: 11:39
 */

Yii::import('application.components.widgets.gridView.CheckBoxColumn');
Yii::import('zii.widgets.grid.CGridView');

/**
 * Class GridViewEx Оригинальный класс GridView. Разница только в том, что
 * этот класс наследует от CGridView. Сделано для того, чтобы впилить
 * виджет без подключенного компоннета bootstrap.
 */
class GridViewEx extends CGridView {

    public $groupActions = array();
    public $afterGroupAction;
    public $groupCheckboxValue = 'id';
    public $groupCheckboxClass = 'group-checkbox-column';
    public $groupCheckboxName = 'group-checkbox-column[]';
    public $groupCheckboxDisabled;
    public $summaryRightText;
    public $summaryLeftText;
    public $summaryTopText;
    public $footerText;
    public $enablePageSizeSelector = false;
    public $breadcrumbs = false;
    public $type = 'striped bordered condensed';
    public $template="{summary}\n{items}";

    public $breadCrumbsOptions;
    public $groupActionsOptions;
    public $submitButtonOptions = array();
    public $pageSizeOptions = array();

    public function init()
    {
        if($this->enablePageSizeSelector) {
            $key = $this->id.'pageSize';
            if($pageSize = Yii::app()->request->getParam('pageSize')) {
                Yii::app()->user->setParam($key, $pageSize);
                $this->dataProvider->getPagination()->pageSize = $pageSize;
            } else {
                if($pageSize = Yii::app()->user->getParam($key, 25)) {
                    $this->dataProvider->getPagination()->pageSize = $pageSize;
                }
            }
        }
        $groupValue = $this->groupCheckboxValue;
        $groupName = substr($this->groupCheckboxName, -2) == '[]'
            ? $this->groupCheckboxName
            : ($this->groupCheckboxName . '[]');

        // Добавляем неявно столбец с чекбоксами во все таблицы
        if (count($this->groupActions)) {
            array_unshift($this->columns, array(
                'class'               => 'CheckBoxColumn',
                'selectableRows'      => 2,
                'disabled'            => $this->groupCheckboxDisabled,
                'checkBoxHtmlOptions' => ['name' => $groupName, 'class' => 'ace'],
                'htmlOptions'         => ['class' => $this->groupCheckboxClass],
                'value'               => function ($data) use ($groupValue) {
                        return $data->$groupValue;
                    },
            ));
            if ($this->afterGroupAction === null) {
                $this->afterGroupAction = "
                    $.fn.yiiGridView.update('{$this->id}');
                ";
            }
        }

        $actionLinks = array();
        foreach($this->groupActions as $k=>$v) {
            $keys = explode(':', $k);
            $k = array_pop($keys);
            $actionLinks[$k] = Yii::app()->controller->createUrl($k);
        }
        $actionLinks = json_encode($actionLinks);

        if(count($this->groupActions)) {
            if(!$this->afterAjaxUpdate)
                $this->afterAjaxUpdate = 'function(){addClickHook()}';

            $this->selectableRows = false;
            Yii::app()->clientScript->registerScript('go', "
                function addClickHook()
                {
                    jQuery('table tbody td:not(.buttons)').click(function(e){
                        if(e.target.type == 'checkbox' || e.target.type == '' || e.target.type == 'a')
                          return;
                        var cb = jQuery(this).parent().find('td input[type=checkbox]');
                        cb.attr('checked', !cb.attr('checked'));
                    });
                }
                jQuery(document).ready(function(){
                    addClickHook();
                });
                var actionLinks = $actionLinks;
                function runGroupOperation()
                {
                    var select = jQuery('#group-actions');
                    var action = select.val();
                    var submit = jQuery('#group-operation-submit');
                    submit.attr('disabled', 'disabled');

                    var process = function(action){
                        if(action.indexOf('js:') == 0) {
                            //javascript action
                            eval(action.substring(3));
                        } else if(action.indexOf('popup:') == 0) {
                            jQuery.ajax({
                                url: actionLinks[action.substring(6)],
                                type: 'POST',
                                data: jQuery('.{$this->groupCheckboxClass} input').serializeArray(),
                                complete: function(){
                                    submit.removeAttr('disabled');
                                },
                                success: function(data){
                                    jQuery.fancybox.open(data);
                                }
                            });
                        } else {
                            //ajax action
                            jQuery.ajax({
                                url: actionLinks[action],
                                type: 'POST',
                                data: jQuery('.{$this->groupCheckboxClass} input').serializeArray(),
                                complete: function(){
                                    submit.removeAttr('disabled');
                                },
                                success: function(){
                                    $this->afterGroupAction
                                }
                            });
                        }
                    };

                    if(action.indexOf('confirm:') == 0) {
                        action = action.substring(8);
                        bootbox.confirm('Are you sure?', function(confirmed){
                            if(confirmed) {
                                process(action);
                                jQuery.fileManager.reload();
                            }
                        });
                    } else {
                        process(action);
                    }
                }
            ", CClientScript::POS_HEAD);
        }
        parent::init();
    }

    /**
     *### .initColumns()
     *
     * Creates column objects and initializes them.
     */
    protected function initColumns()
    {
        foreach ($this->columns as $i => $column) {
            if (is_array($column) && !isset($column['class'])) {
                $this->columns[$i]['class'] = 'application.core.components.widgets.gridView.DataColumn';
            }
        }

        parent::initColumns();

        if (isset($this->responsiveTable) && $this->responsiveTable) {
            $this->writeResponsiveCss();
        }
    }

    public function renderSummary()
    {
        $count=$this->dataProvider->getItemCount();

        echo '<div class="' . $this->summaryCssClass . '">';
        if (!is_null($this->summaryTopText)) {
            echo $this->evaluateData($this->summaryTopText);
        }
        if(!is_null($this->summaryLeftText)) {
            echo Html::tag('div', array('class'=>'pull-left'), $this->evaluateData($this->summaryLeftText));
        }
        if($this->breadcrumbs) {
            $this->widget('TbBreadcrumbs', array(
                'links'       => $this->breadcrumbs,
                'homeLink'    => false,
                'encodeLabel' => false,
                'separator'   => null,
                'htmlOptions' => ($this->breadCrumbsOptions)
                                 ? $this->breadCrumbsOptions
                                 : ['class' => 'pull-left breadcrumb', 'style' => 'margin: 3px 0 0 21px;']
            ));
        }
        if($this->enablePagination)
        {
            $pagination=$this->dataProvider->getPagination();
            $total=$this->dataProvider->getTotalItemCount();
            $start=$pagination->currentPage*$pagination->pageSize+1;
            $end=$start+$count-1;
            if($end>$total)
            {
                $end=$total;
                $start=$end-$count+1;
            }
            if(($summaryText=$this->summaryText)===null)
                $summaryText=Yii::t('zii','Displaying {start}-{end} of 1 result.|Displaying {start}-{end} of {count} results.',$total);
            $summaryText = strtr($summaryText,array(
                '{start}'=>$start,
                '{end}'=>$end,
                '{count}'=>$total,
                '{page}'=>$pagination->currentPage+1,
                '{pages}'=>$pagination->pageCount,
            ));
        }
        else
        {
            if(($summaryText=$this->summaryText)===null)
                $summaryText=Yii::t('zii','Total 1 result.|Total {count} results.',$count);

            $summaryText = strtr($summaryText,array(
                '{count}'=>$count,
                '{end}'=>$count,
            ));
        }

        if($this->enablePageSizeSelector) {
            $data = is_array($this->enablePageSizeSelector)? $this->enablePageSizeSelector : array(
                '10'=>10,
                '25'=>25,
                '50'=>50,
               '100'=>100,
            );

            echo Html::beginForm(Yii::app()->request->baseUrl . '/' . Yii::app()->request->pathInfo, 'post', array(
                'class'=>'form form-inline',
                'style'=>'margin-bottom:0',
            ));
            echo $summaryText;
            if($summaryText) {
                echo '&nbsp;&nbsp;&nbsp;';
            }

            if ($this->dataProvider->getPagination()) {
                echo 'Page size: ';
                echo Html::dropDownList(
                    'pageSize',
                    $this->dataProvider->getPagination()->pageSize,
                    $data,
                    $this->pageSizeOptions + array(
                        'onchange'=>"
                            jQuery.fn.yiiGridView.update('{$this->id}', {
                                url: jQuery.param.querystring(jQuery.fn.yiiGridView.getUrl('{$this->id}'), {pageSize: jQuery(this).val()})
                            });
                        ",
                        'style'=>'width:70px;',
                    )
                );
            }
            echo $this->evaluateData($this->summaryRightText);
            echo Html::endForm();
        } else {
            echo $summaryText;
        }
        echo '</div>';
    }

    public function evaluateData($expression)
    {
        if(is_callable($expression)) {
            return $this->evaluateExpression($expression);
        }
        return $expression;
    }

    public function renderTableHeader()
    {
        parent::renderTableHeader(); // TODO: Change the autogenerated stub

        Yii::app()->clientScript->registerScript('headerCLick', '
            $(document).on("mousedown", "table.dataTable th[class*=sort]", function(){
                $(this).find("a").click();
                return false;
            });
        ', CClientScript::POS_READY);
    }


    /**
     * Renders the table footer.
     */
    public function renderTableFooter()
    {
        $hasFilter=$this->filter!==null && $this->filterPosition===self::FILTER_POS_FOOTER;
        $hasFooter=$this->getHasFooter();

        echo "<tfoot>\n";
        if($hasFooter)
        {
            echo "<tr>\n";
            foreach($this->columns as $column)
                $column->renderFooterCell();
            echo "</tr>\n";
        }
        if($hasFilter)
            $this->renderFilter();

        if($this->groupActions || ($this->pager && $this->dataProvider->pagination &&
                $this->dataProvider->pagination->getPageCount() > 1))
        {
            echo "<tr class='group-actions'>";
            if(count($this->groupActions)) {
                echo '<td>';
                echo '<label>';
                echo Html::checkBox('footer-check-all', false, array(
                    'id'=>'footer-check-all',
                    'class'=>$this->id.'_check_all ace',
                ));
                echo '<span class="lbl"></span></label>';
                echo '</td>';
            }
            echo "<td colspan='100%' style='text-align:left;'>";
            echo '<div class="pull-right">';
            $this->renderPager();
            echo '</div>';

            if(count($this->groupActions)) {
                echo CHtml::dropDownList(
                    'group-actions',
                    null,
                    array(null=>'Select action')+$this->groupActions,
                    ($this->groupActionsOptions)
                        ? $this->groupActionsOptions
                        : ['style' => 'margin:0 5px 0 0;']
                );
                $this->widget('TbButton', array(
                    'id'          => 'group-operation-submit',
                    'label'       => 'Submit',
                    'htmlOptions' => $this->submitButtonOptions + array(
                            'onclick' => 'runGroupOperation()',
                        ),
                ));
            }

            echo $this->evaluateData($this->footerText);
            echo "</td>";
            echo "</tr>";
        }

        if(is_null($this->afterGroupAction)) {
            $this->afterGroupAction = "function(data){jQuery('#{$this->id}').yiiGridView('update');}";
        }

        echo "</tfoot>\n";
    }
}
