<?php
/* @var $this LeadsourceController */
/* @var $model Leadsource */

$this->pageTitle = "Источники лидов. " . Yii::app()->name;


$this->breadcrumbs=array(
	'Источники лидов'=>array('index'),
	'Новый',
);
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('Кабинет вебмастера',"/webmaster/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1>Новый источник лидов</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>