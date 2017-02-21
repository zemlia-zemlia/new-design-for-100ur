<?php
/* @var $this YurCompanyController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = CHtml::encode("Юридические компании" . Yii::app()->name);

$this->breadcrumbs=array(
	'Юридические компании'=>array('index'),
	$model->name,
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'      =>  CHtml::link('CRM',"/"),
    'separator'     =>  ' / ',
    'links'         =>  $this->breadcrumbs,
 ));
?>

<h1>Юридические компании</h1>

<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th style="width:50px;"></th>
        <th>Компания</th>
        <th>Город</th>
        <th>Сайт</th>
        <th>Автор</th>
    </tr>
    </thead>
    <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'  =>  $dataProvider,
            'itemView'      =>  '_view',
    )); ?>
</table>
