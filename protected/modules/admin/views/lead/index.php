<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Лиды. " . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile('/js/lead.js');
        
$this->breadcrumbs=array(
	'Лиды',
);

?>
<div  class="vert-margin30">
<h1>Центр Обработки Обращений
        <?php echo CHtml::link('Отфильтровать обращения', Yii::app()->createUrl('/admin/lead/sendLeads'), array('class'=>'btn btn-primary'));?>

</h1>
    
    <?php echo CHtml::link('Сгенерировать тестовых лидов', Yii::app()->createUrl('/admin/lead/generate'));?>
</div>

<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Текст лида</th>
        <th>Вопрос</th>
    </tr>
    </thead>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'emptyText' =>  'Не найдено ни одного лида',
        'summaryText'=>'Показаны лиды с {start} до {end}, всего {count}',
        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
</table>
