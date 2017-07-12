<?php
/* @var $this LeadsourceController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Источники лидов. " . Yii::app()->name;


$this->breadcrumbs=array(
	'Источники лидов',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Источники лидов.
<?php echo CHtml::encode($office->name); ?>
</h1>

<div class="right-align">
    <?php echo CHtml::link("Добавить новый", Yii::app()->createUrl('admin/leadsource/create'), array('class'=>'btn btn-primary'));?>
</div>

<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Пользователь</th>
        <th>Описание</th>
        <th></th>
    </tr>
    </thead>
    
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
        'emptyText' =>  'Не найдено ни одного контакта',
        'summaryText'=>'Показаны контакты с {start} до {end}, всего {count}',
        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
</table>
