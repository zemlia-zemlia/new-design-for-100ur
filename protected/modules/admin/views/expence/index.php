<?php
    $this->setPageTitle("Расходы. ". Yii::app()->name);
?>

<h1 class="vert-margin30">Расходы 
    <?php echo CHtml::link('добавить', Yii::app()->createUrl('admin/expence/create'), array('class' => 'btn btn-success'));?>
</h1>

<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Дата</th>
        <th>Статья</th>
        <th>Сумма</th>
        <th>Комментарий</th>
        <th></th>
    </tr>
    </thead>
<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'  =>  $dataProvider,
    'itemView'      =>  '_view',
        'emptyText'     =>  'Не найдено ни одного расхода',
        'summaryText'   =>  'Показаны расходы с {start} до {end}, всего {count}',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
</table>