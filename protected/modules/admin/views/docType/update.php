<?php
/* @var $this DocTypeController */
/* @var $model DocType */

$this->breadcrumbs=array(
    'Типы документов'   =>  array('index'),
    $model->name        =>  array('view','id'=>$model->id),
    'Редактирование',
);

?>
<?php

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов', "/admin"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1>Редактирование типа документа</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>