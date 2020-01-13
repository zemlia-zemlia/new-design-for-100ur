<?php
/* @var $this FileCategoryController */
/* @var $model FileCategory */

$this->breadcrumbs=array(
	'File Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List FileCategory', 'url'=>array('index')),
	array('label'=>'Manage FileCategory', 'url'=>array('admin')),
);
?>

<h1>Create FileCategory</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>