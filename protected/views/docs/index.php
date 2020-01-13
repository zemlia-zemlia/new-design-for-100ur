<?php
/* @var $this DocsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Docs',
);

$this->menu=array(
	array('label'=>'Create Docs', 'url'=>array('create')),
	array('label'=>'Manage Docs', 'url'=>array('admin')),
);
?>
<?php var_dump(FileCategory::model()->findAll(array('order'=>'lft')));die;?>


<h1>Docs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
