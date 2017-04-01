<?php
/* @var $this MoneyController */
/* @var $dataProvider CActiveDataProvider */
$this->setPageTitle("Касса. " . Yii::app()->name);

$sumBalance = 0;

?>

<h1 class="vert-margin20">Касса  
    <?php echo CHtml::link("Добавить запись", Yii::app()->createUrl("/admin/money/create"), array('class'=>'btn btn-primary'));?>
    <?php echo CHtml::link("Добавить транзакцию", Yii::app()->createUrl("/admin/money/addTransaction"), array('class'=>'btn btn-primary'));?>
    <?php echo CHtml::link("Отчет", Yii::app()->createUrl("/admin/money/report"), array('class'=>'btn btn-default'));?>
</h1>

<div class="row">
    <?php foreach($accounts as $acId=>$account):?>
        <div class="col-sm-2 center-align">
            <div class="panel panel-warning">
                <div class="panel-heading"><?php echo $account;?></div>
                <div class="panel-body">
                    <h4><?php echo number_format($balances[$acId], 0, '.', ' ');?></h4>
                </div>
            </div>
        </div>
        <?php $sumBalance += $balances[$acId];?>
    <?php endforeach;?>
    <div class="col-sm-2 center-align">
        <div class="panel panel-success">
            <div class="panel-heading">Всего</div>
            <div class="panel-body">
                <h4><?php echo number_format($sumBalance, 0, '.', ' ');?></h4>
            </div>
            
        </div>
    </div>
</div>

<table class="table table-bordered">
    
    <tr>
        <th>#</th>
        <th>Дата</th>
        <th>Статья</th>
        <th>Счет</th>
        <th>Сумма</th>
        <th>Комментарий</th>
    </tr>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $dataProvider,
	'itemView'      =>  '_view',
        'emptyText'     =>  'Не найдено ни одной записи',
        'summaryText'   =>  'Показаны записи с {start} до {end}, всего {count}',
        'pager'         =>  array('class'=>'GTLinkPager'), //we use own pager with russian words
)); ?>
</table>