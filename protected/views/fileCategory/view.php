<?php
/* @var $this FileCategoryController */
/* @var $model FileCategory */

$this->breadcrumbs=array(
	'File Categories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List FileCategory', 'url'=>array('index')),
	array('label'=>'Create FileCategory', 'url'=>array('create')),
	array('label'=>'Update FileCategory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete FileCategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage FileCategory', 'url'=>array('admin')),
);
?>

<h1>View FileCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'lft',
		'rgt',
		'root',
		'level',
	),
)); ?>
