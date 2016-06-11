<?php
/* @var $this YurCompanyController */
/* @var $model YurCompany */

$this->setPageTitle("Новая юр.компания" . " | ". Yii::app()->name);

$this->breadcrumbs=array(
	'Юр.компании'=>array('index'),
	'Создание',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('CRM',"/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Новая юридическая компания</h1>

<?php $this->renderPartial('_form', array(
        'model'=>$model,
    )); ?>