<?php
/* @var $this CampaignController */
/* @var $model Campaign */

$this->breadcrumbs=array(
	'Campaigns'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Campaign', 'url'=>array('index')),
	array('label'=>'Manage Campaign', 'url'=>array('admin')),
);
?>

<h1>Create Campaign</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>