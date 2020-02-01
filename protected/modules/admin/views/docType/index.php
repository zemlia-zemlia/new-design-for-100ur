<?php
/* @var $this DocTypeController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Типы документов. " . Yii::app()->name;

$this->breadcrumbs=array(
    'Типы документов',
);

?>

<h1>Типы документов для заказа</h1>

<div class="right-align vert-margin30">
    <?php echo CHtml::link('Добавить тип', Yii::app()->createUrl('admin/docType/create'), ['class' => 'btn btn-primary']);?>
</div>

<table class="table table-bordered">
    <tr>
        <th>id</th>
        <th>Раздел</th>
        <th>Наименование</th>
        <th>Мин.цена</th>
    </tr>

    <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider'=>$dataProvider,
            'itemView'=>'_view',
    )); ?>
</table>
