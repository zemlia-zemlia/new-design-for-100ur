<?php
/* @var $this DocTypeController */
/* @var $model DocType */
$this->pageTitle = "Новый тип документа. " . Yii::app()->name;
$this->breadcrumbs=array(
    'Типы документов'=>array('index'),
    'Создание',
);

?>

<h1>Новый тип документа</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>