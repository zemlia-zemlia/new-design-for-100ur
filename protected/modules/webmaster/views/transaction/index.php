<?php
    $this->pageTitle = "Транзакции. " . Yii::app()->name;
?>

<div  class="vert-margin30">
<h1>Кабинет вебмастера. Мои транзакции</h1>
</div>

<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Дата</th>
        <th>Сумма</th>
        <th>Комментарий</th>
    </tr>
    </thead>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'emptyText' =>  'Не найдено ни одной транзакции',
        'summaryText'=>'Показаны транзакции с {start} до {end}, всего {count}',
        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
</table>
