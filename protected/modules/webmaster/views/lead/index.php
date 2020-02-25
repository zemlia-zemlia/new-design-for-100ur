<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Лиды. " . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile('/js/lead.js');
Yii::app()->clientScript->registerScriptFile('/js/admin/lead.js');
        
$this->breadcrumbs=array(
    'Лиды',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('Кабинет вебмастера', "/webmaster/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Мои лиды
    <?php if (sizeof(Leadsource::getSourcesArrayByUser(Yii::app()->user->id))>0):?>
    <?php echo CHtml::link('Добавить лид вручную', Yii::app()->createUrl('/webmaster/lead/create'), array('class' => 'btn btn-primary'));?>
    <?php endif;?>
</h1>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
        'emptyText' =>  'Не найдено ни одного лида',
        'summaryText'=>'Показаны лиды с {start} до {end}, всего {count}',
        'pager'=>array('class'=>'GTLinkPager') //we use own pager with russian words
)); ?>
