<?php
/* @var $this LeadsourceController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Источники лидов. " . Yii::app()->name;


$this->breadcrumbs=array(
    'Источники лидов',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('Кабинет вебмастера', "/webmaster/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1 class="vert-margin20">Источники лидов   <?php echo CHtml::link("Добавить новый", $this->createUrl('create'), array('class'=>'btn btn-primary btn-sm'));?></h1>


<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Описание</th>
        <th></th>
    </tr>
    </thead>
    
<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
        'emptyText' =>  'Не найдено ни одного источника',
        'summaryText'=>'Показаны источники с {start} до {end}, всего {count}',
        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
</table>
