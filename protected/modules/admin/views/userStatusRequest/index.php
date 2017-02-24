<?php
/* @var $this UserStatusRequestController */
/* @var $dataProvider CActiveDataProvider */
$this->pageTitle = "Запросы на изменение статуса. " . Yii::app()->name;


$this->breadcrumbs=array(
	'Запросы на изменение статуса',
);

Yii::app()->clientScript->registerScriptFile('/js/admin/statusRequest.js');


?>
<div  class="vert-margin30">
    <h1>Запросы на изменение статуса</h1>
</div>


<table class="table table-bordered">
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
</table>
