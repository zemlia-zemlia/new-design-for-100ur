<?php
/* @var $this CodecsController */
/* @var $model Codecs */

$this->breadcrumbs=array(
	'Codecs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Codecs', 'url'=>array('index')),
	array('label'=>'Create Codecs', 'url'=>array('create')),
	array('label'=>'View Codecs', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Codecs', 'url'=>array('admin')),
);
?>

<h1>Update Codecs <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>