<?php
/* @var $this LeadController */
/* @var $model Lead100 */

$this->breadcrumbs=array(
	'Leads'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Lead100', 'url'=>array('index')),
	array('label'=>'Manage Lead100', 'url'=>array('admin')),
);
?>

<h1>Create Lead100</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>