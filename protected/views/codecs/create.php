<?php
/* @var $this CodecsController */
/* @var $model Codecs */

$this->breadcrumbs=array(
	'Codecs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Codecs', 'url'=>array('index')),
	array('label'=>'Manage Codecs', 'url'=>array('admin')),
);
?>

<h1>Create Codecs</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>