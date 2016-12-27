<?php
/* @var $this LeadsourceController */
/* @var $model Leadsource */

$this->pageTitle = "Источники контактов. " . Yii::app()->name;


$this->breadcrumbs=array(
	'Источники контактов'=>array('index'),
	'Новый',
);
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1>Новый источник контактов</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>