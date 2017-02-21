<?php
/* @var $this LeadsourceController */
/* @var $model Leadsource */

$this->breadcrumbs=array(
	'Источники'=>array('index'),
	$model->name,
);

?>

<h1>View Leadsource #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'description',
	),
)); ?>
