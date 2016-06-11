<?php
/* @var $this YurCompanyController */
/* @var $model YurCompany */

$this->setPageTitle("Редактирование компании" . " | ". Yii::app()->name);

$this->breadcrumbs=array(
	'Юр.компании'               =>  array('index'),
        CHtml::encode($model->name) =>  array('view', 'id'=>$model->id),
	'Редактирование',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1><?php echo CHtml::encode($model->name); ?></h1>

<?php $this->renderPartial('_form', array(
        'model'=>$model,
    )); 
?>