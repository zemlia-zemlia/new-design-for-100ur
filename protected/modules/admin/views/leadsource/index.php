<?php
/* @var $this LeadsourceController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = 'Источники лидов. ' . Yii::app()->name;

$this->breadcrumbs = [
    'Источники лидов',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);

?>
<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 1px;
    }
</style>


<h3>Источники лидов.
    <?php echo CHtml::encode($office->name); ?>
    <?php echo CHtml::link('Добавить новый', Yii::app()->createUrl('admin/leadsource/create'), ['class' => 'btn btn-primary']); ?>
</h3>

<div class="box">
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Пользователь</th>
            <th>Описание</th>
            <th></th>
        </tr>
        </thead>

        <?php $this->widget('zii.widgets.CListView', [
            'dataProvider' => $dataProvider,
            'itemView' => '_view',
            'emptyText' => 'Не найдено ни одного контакта',
            'summaryText' => 'Показаны контакты с {start} до {end}, всего {count}',
            'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
        ]); ?>
    </table>
</div>