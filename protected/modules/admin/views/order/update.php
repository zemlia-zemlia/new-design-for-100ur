<?php
/* @var $this OrderController */
/* @var $model Order */
$this->setPageTitle("Редактирование заказа документов #" . $model->id . '. ' . Yii::app()->name);

$this->breadcrumbs=array(
	'Заказы документов'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Редактирование',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 Юристов',"/admin"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1>Редактирование заказа документов #<?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>