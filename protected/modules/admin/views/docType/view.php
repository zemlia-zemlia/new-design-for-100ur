<?php
/* @var $this DocTypeController */
/* @var $model DocType */
$this->pageTitle = "Типы документов: " . CHtml::encode($model->name) . '. ' . Yii::app()->name;

$this->breadcrumbs=array(
    'Типы документов'=>array('index'),
    $model->name,
);
?>

<?php

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов', "/admin"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

?>

<h1><?php echo CHtml::encode($model->name); ?></h1>

<table class="table table-bordered">
    <tr>
        <td>id</td>
        <td><?php echo $model->id;?></td>
    </tr>
    <tr>
        <td>Раздел</td>
        <td><?php echo $model->getClassName();?></td>
    </tr>
    <tr>
        <td>Наименование</td>
        <td><?php echo CHtml::encode($model->name);?></td>
    </tr>
    <tr>
        <td>Минимальная цена</td>
        <td><?php echo $model->minPrice;?> руб.</td>
    </tr>
</table>

<?php echo CHtml::link('Редактировать', Yii::app()->createUrl('admin/docType/update', ['id' => $model->id]));?>