<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Лиды. " . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile('/js/lead.js');
Yii::app()->clientScript->registerScriptFile('/js/admin/lead.js');
        
$this->breadcrumbs=array(
	'Лиды',
);

?>
<div class="vert-margin30">
   <?php $this->renderPartial('_searchForm', array('model'=>$searchModel));?> 
</div>

<div  class="vert-margin30">
<h1>Лиды
    <?php if(Yii::app()->user->role == User::ROLE_ROOT):?>
        <?php echo CHtml::link('Отфильтровать обращения', Yii::app()->createUrl('/admin/lead/sendLeads'), array('class'=>'btn btn-primary'));?>
    <?php endif;?>
</h1>
    
    <?php if(Yii::app()->user->role == User::ROLE_ROOT && YII_DEBUG === true):?>
    <?php echo CHtml::link('Сгенерировать тестовых лидов', Yii::app()->createUrl('/admin/lead/generate'));?>
    <?php endif;?> 
</div>

<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th></th>
        <th>Текст лида</th>
        <th>Управление</th>
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
