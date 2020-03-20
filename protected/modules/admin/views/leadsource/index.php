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


<h3>Источники лидов.
    <?php echo CHtml::link('Добавить новый', Yii::app()->createUrl('admin/leadsource/create'), ['class' => 'btn btn-primary']); ?>
</h3>

<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
                <div class="box-title">Активные</div>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Url</th>
                        <th>Пользователь</th>
                        <th>Управление</th>
                    </tr>
                    </thead>

                    <?php $this->widget('zii.widgets.CListView', [
                        'dataProvider' => $dataProviderActive,
                        'itemView' => '_view',
                        'emptyText' => 'Не найдено ни одного контакта',
                        'summaryText' => 'Показаны контакты с {start} до {end}, всего {count}',
                        'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
                    ]); ?>
                </table>
            </div>
        </div>
<div class="alert alert-info">
    Активные - это источники по которым были лиды за последние 5 суток.
</div>
    </div>

    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
                <div class="box-title">Не активные</div>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Url</th>
                        <th>Пользователь</th>
                        <th>Управление</th>
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
        </div>
    </div>
</div>



