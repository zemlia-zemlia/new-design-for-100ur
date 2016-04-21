<?php
// проверяет, является ли один из дочерних разделов активным. возвращает true, если является
    function hasActiveChild($data, $sectionName)
    {
        // если нет детей, сразу возвращаем false
        if(!sizeof($data->children)) {
            return false;
        }
        
        foreach ($data->children as $child) {
            if(isset($sectionName) && $sectionName == $child->alias) {
                return true;
            }
        }
        
        return false;
    }
?>

<ul id="left-menu">
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'emptyText'     =>  'Не найдено ни одной категории',
        'summaryText'   =>  '',
)); ?>
</ul>

