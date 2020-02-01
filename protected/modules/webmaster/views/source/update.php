<?php
/* @var $this LeadsourceController */
/* @var $model Leadsource */

$this->pageTitle = "Редактирование источника контактов. " . Yii::app()->name;


$this->breadcrumbs=array(
    'Источники контактов'=>array('index'),
    $model->name=>array('view','id'=>$model->id),
    'Редактирование',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов', "/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));
?>

<h1>Редактирование источника контактов</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>