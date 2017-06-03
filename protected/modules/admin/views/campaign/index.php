<?php
/* @var $this CampaignController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Кампании',
);

$this->pageTitle = "Кампании. " . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile('/js/admin/campaign.js');

?>

<h1>Кампании <?php echo CHtml::link('Создать кампанию', Yii::app()->createUrl('/admin/campaign/create'), array('class'=>'btn btn-primary')); ?></h1>

<table class="table table-bordered">
    
    <thead>
        <tr>
            <th></th>
            <th>Регион</th>
            <th><span class="glyphicon glyphicon-time"></span></th>
            <th>%&nbsp;брака</th>
            <th>Цена</th>
            <th>Отправлено</th>
        </tr>
    </thead>    
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $dataProvider,
	'itemView'      =>  '_view',
        'viewData'      =>  array(
            'showInactive'  =>  $showInactive,
        ),
)); ?>
</table>

<?php 
if(!$showInactive) {
    echo CHtml::link('Показать неактивные', Yii::app()->createUrl('admin/campaign/index', array('show_inactive' => true)));
}
?>

<h2>На модерации</h2>

<table class="table table-bordered">
    
    <thead>
        <tr>
            <th>Регион</th>
            <th><span class="glyphicon glyphicon-time"></span></th>
            <th>%&nbsp;брака</th>
            <th>Цена</th>
            <th>Отправлено</th>
        </tr>
    </thead>    
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'  =>  $dataProviderModeration,
	'itemView'      =>  '_viewCampaign',
)); ?>
</table>

