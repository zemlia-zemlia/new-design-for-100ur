<?php
/* @var $this DocsController */
/* @var $model Docs */

$this->breadcrumbs=array(
	'Docs'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Docs', 'url'=>array('index')),
	array('label'=>'Create Docs', 'url'=>array('create')),
	array('label'=>'View Docs', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Docs', 'url'=>array('admin')),
);
?>

<h1>Update Docs <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>