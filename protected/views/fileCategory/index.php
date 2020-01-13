<?php
/* @var $this FileCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'File Categories',
);

$this->menu=array(
	array('label'=>'Create FileCategory', 'url'=>array('create')),
	array('label'=>'Manage FileCategory', 'url'=>array('admin')),
);
?>

<h1>File Categories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
