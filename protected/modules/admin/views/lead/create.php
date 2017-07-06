<?php
/* @var $this LeadController */
/* @var $model Lead100 */

$this->setPageTitle("Новый лид". Yii::app()->name);


$this->breadcrumbs=array(
	'Лиды'=>array('index'),
	'Добавление',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 Юристов',"/admin"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1>Новый лид</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
