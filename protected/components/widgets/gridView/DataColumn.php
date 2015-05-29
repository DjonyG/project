<?php
/**
 * Created by PhpStorm.
 * User: serg
 * Date: 12/17/13
 * Time: 1:12 PM
 */

class DataColumn extends TbDataColumn {

    /**
     * Renders the header cell.
     */
    public function renderHeaderCell()
    {
        $this->headerHtmlOptions['id']=$this->id;
        $sort = $this->grid->dataProvider->getSort();

        if ($this->grid->enableSorting && $this->sortable && $this->name !== null
            && (!$sort->attributes || in_array($this->name, $sort->attributes))) {
            $this->headerHtmlOptions['class'] = 'sorting';
            if ($sort->resolveAttribute($this->name) !== false){
                if($sort->getDirection($this->name) === CSort::SORT_ASC){
                    $this->headerHtmlOptions['class'] = 'sorting_asc';
                } elseif($sort->getDirection($this->name) === CSort::SORT_DESC){
                    $this->headerHtmlOptions['class'] = 'sorting_desc';
                }
            }
        }
        echo CHtml::openTag('th',$this->headerHtmlOptions);
        $this->renderHeaderCellContent();
        echo "</th>";
    }

    /**
     *### .renderHeaderCellContent()
     *
     * Рендерит ячейку в заголовке таблицы.
     */
    protected function renderHeaderCellContent()
    {
        if ($this->grid->enableSorting && $this->sortable && $this->name !== null) {
            $sort = $this->grid->dataProvider->getSort();
            $label = isset($this->header) ? $this->header : $sort->resolveLabel($this->name);

            echo $sort->link($this->name, $label, array('class' => 'sort-link'));
        } else {
            if ($this->name !== null && $this->header === null) {
                if ($this->grid->dataProvider instanceof CActiveDataProvider) {
                    echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
                } else {
                    echo CHtml::encode($this->name);
                }
            } else {
                parent::renderHeaderCellContent();
            }
        }
    }
}