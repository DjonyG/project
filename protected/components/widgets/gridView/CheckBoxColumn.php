<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hett
 * Date: 25.04.13
 * Time: 12:29
 */

class CheckBoxColumn extends CCheckBoxColumn {


    /**
     * Initializes the column.
     * This method registers necessary client script for the checkbox column.
     */
    public function init()
    {
        if(isset($this->checkBoxHtmlOptions['name']))
            $name=$this->checkBoxHtmlOptions['name'];
        else
        {
            $name=$this->id;
            if(substr($name,-2)!=='[]')
                $name.='[]';
            $this->checkBoxHtmlOptions['name']=$name;
        }
        $name=strtr($name,array('['=>"\\[",']'=>"\\]"));

        if($this->selectableRows===null)
        {
            if(isset($this->checkBoxHtmlOptions['class']))
                $this->checkBoxHtmlOptions['class'].=' select-on-check';
            else
                $this->checkBoxHtmlOptions['class']='select-on-check';
            return;
        }

        $cball=$cbcode='';
        if($this->selectableRows==0)
        {
            //.. read only
            $cbcode="return false;";
        }
        elseif($this->selectableRows==1)
        {
            //.. only one can be checked, uncheck all other
            $cbcode="jQuery(\"input:not(#\"+this.id+\")[name='$name']\").prop('checked',false);";
        }
        elseif(strpos($this->headerTemplate,'{item}')!==false)
        {
            //.. process check/uncheck all
            $cball=<<<CBALL
jQuery(document).on('click','.{$this->grid->id}_check_all',function() {
	var checked=this.checked;
	jQuery("tr input[name='$name'], .{$this->grid->id}_check_all").each(function() {this.checked=checked;});
	jQuery("tr[data-folder=yes] input[name='$name']").each(function(index, elem){
	    $(elem).prop('checked', false);
	});
});

CBALL;
            $cbcode="jQuery('.{$this->grid->id}_check_all').prop('checked', jQuery(\"input[name='$name']\").length==jQuery(\"input[name='$name']:checked\").length);";
        }

        if($cbcode!=='')
        {
            $js=$cball;
            $js.=<<<EOD
jQuery(document).on('click', "input[name='$name']", function() {
	$cbcode
});
EOD;
            Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id,$js);
        }
    }

    /**
     * Renders the header cell content.
     * This method will render a checkbox in the header when {@link selectableRows} is greater than 1
     * or in case {@link selectableRows} is null when {@link CGridView::selectableRows} is greater than 1.
     */
    protected function renderHeaderCellContent()
    {
        if(trim($this->headerTemplate)==='')
        {
            echo $this->grid->blankDisplay;
            return;
        }

        $item = '';
        if ($this->selectableRows === null && $this->grid->selectableRows > 1) {
            $item = '<label>';
            $item .= CHtml::checkBox($this->grid->id . '_check_all', false, array('class' => 'ace select-on-check-all', 'style'));
            $item .= '<span class="lbl"></span></label>';
        } elseif ($this->selectableRows > 1) {
            $item =
            $item = '<label>';
            $item .= CHtml::checkBox($this->grid->id . '_check_all', false, array('class' => $this->grid->id . '_check_all ace'));
            $item .= '<span class="lbl"></span></label>';
        } else {
            ob_start();
            parent::renderHeaderCellContent();
            $item = ob_get_clean();
        }

        echo strtr($this->headerTemplate,array(
            '{item}'=>$item,
        ));
    }

    /**
     * Renders the data cell content.
     * This method renders a checkbox in the data cell.
     * @param integer $row the row number (zero-based)
     * @param mixed $data the data associated with the row
     */
    protected function renderDataCellContent($row,$data)
    {
        if($this->value!==null)
            $value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
        elseif($this->name!==null)
            $value=CHtml::value($data,$this->name);
        else
            $value=$this->grid->dataProvider->keys[$row];

        $checked = false;
        if($this->checked!==null)
            $checked=$this->evaluateExpression($this->checked,array('data'=>$data,'row'=>$row));

        $options=$this->checkBoxHtmlOptions;
        if($this->disabled!==null)
            $options['disabled']=$this->evaluateExpression($this->disabled,array('data'=>$data,'row'=>$row));

        $name=$options['name'];
        unset($options['name']);
        $options['value']=$value;
        $options['id']=$this->id.'_'.$row;

        echo '<label>';
        echo CHtml::checkBox($name,$checked,$options);
        echo '<span class="lbl"></span></label>';
    }


}