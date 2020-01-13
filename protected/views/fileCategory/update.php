<?php
/* @var $this FileCategoryController */
/* @var $model FileCategory */

$this->breadcrumbs=array(
	'File Categories'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List FileCategory', 'url'=>array('index')),
	array('label'=>'Create FileCategory', 'url'=>array('create')),
	array('label'=>'View FileCategory', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage FileCategory', 'url'=>array('admin')),
);
?>

<h1>Update FileCategory <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>