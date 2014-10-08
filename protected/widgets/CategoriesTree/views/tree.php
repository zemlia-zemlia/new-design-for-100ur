<ul id="left-menu">
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'emptyText'     =>  'Не найдено ни одной категории',
        'summaryText'   =>  '',
)); ?>
</ul>

