<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Вопросы и ответы.". Yii::app()->name);


$this->breadcrumbs=array(
	'Вопросы и ответы',
);
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>
<div class="vert-margin30">
<h1>Вопросы</h1>

<?php if(!is_null($status)):?>
    <h3>Статус: <?php echo Question::getStatusName($status); ?></h3>
<?php endif;?>
</div>


<div class="vert-margin30">
    <?php echo CHtml::link('Вопросы по категориям', Yii::app()->createUrl('questionCategory/index'), array('class'=>'btn btn-primary')); ?>
    <?php echo CHtml::link('<span class="glyphicon glyphicon-plus-sign"></span> Добавить вопрос', Yii::app()->createUrl('question/create'), array('class'=>'btn btn-primary')); ?>
</div>

<div class="vert-margin30">
    Фильтр по статусам: 
<?php foreach(Question::getStatusesArray() as $statusCode=>$statusName):?>
<?php 
    if($statusCode!==$status) {
        echo CHtml::link($statusName, Yii::app()->createUrl('question/index',array('status'=>$statusCode))) . ' &nbsp; ';
    } else {
        echo "<strong>" . $statusName. "</strong>" . ' &nbsp; ';
    }
?>
<?php endforeach;?>
    <?php echo CHtml::link('Все', Yii::app()->createUrl('question/index'));?>
</div>
    
<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Вопрос</th>
        <th>Категория</th>
        <th>Автор</th>
        <th>Статус</th>
    </tr>
    </thead>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'      =>  '_view',
        'emptyText'     =>  'Не найдено ни одного вопроса',
        'summaryText'   =>  'Показаны вопросы с {start} до {end}, всего {count}',
        'pager'         =>  array('class'=>'GTLinkPager') //we use own pager with russian words

)); ?>
</table>
