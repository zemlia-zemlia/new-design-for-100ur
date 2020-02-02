<?php
/* @var $this DocsController */
/* @var $model Docs */

$this->breadcrumbs=array(
	'Docs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Docs', 'url'=>array('index')),
	array('label'=>'Manage Docs', 'url'=>array('admin')),
);
?>

<h1>Загрузить Файл</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>