<?php
/* @var $this DocsController */
/* @var $model Docs */

$this->breadcrumbs=array(
	'Docs'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Docs', 'url'=>array('index')),
	array('label'=>'Create Docs', 'url'=>array('create')),
	array('label'=>'Update Docs', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Docs', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Docs', 'url'=>array('admin')),
);
?>

<h1>View Docs #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'filename',
		'type',
		'downloads_count',
	),
)); ?>
