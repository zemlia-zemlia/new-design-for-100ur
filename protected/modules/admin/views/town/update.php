<?php
/* @var $this TownController */
/* @var $model Town */

$this->pageTitle = "Редактирование города " . CHtml::encode($model->name) . '. ' . Yii::app()->name;


$this->breadcrumbs=array(
	'Города'=>array('index'),
        CHtml::encode($model->name) => array('view', 'id'=>$model->id),
	'Редактирование',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Редактирование города <?php echo CHtml::encode($model->name); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>