<?php
/* @var $this LeadsourceController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = 'Источники лидов. ' . Yii::app()->name;

$this->breadcrumbs = [
    'Источники лидов',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('Кабинет вебмастера', '/webmaster/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);

?>

<h1 class="vert-margin20">Источники</h1>

<div class="row">
    <div class="col-md-9">
        <div class="box">
            <div class="box-header">
                <div class="box-title">Источники лидов:</div>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Описание</th>

                        <th></th>
                    </tr>
                    </thead>

                    <?php $this->widget('zii.widgets.CListView', [
                        'dataProvider' => $dataProvider,
                        'itemView' => '_view',
                        'emptyText' => 'Не найдено ни одного источника',
                        'summaryText' => 'Показаны источники с {start} до {end}, всего {count}',
                        'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
                    ]); ?>
                </table>
            </div>
        </div>

        <div class="box">
            <div class="box-header">
                <div class="box-title">Источники для привлечения вопросов:</div>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Реферальная ссылка</th>
                        <th></th>
                    </tr>
                    </thead>

                    <?php $this->widget('zii.widgets.CListView', [
                        'dataProvider' => $dataProviderQ,
                        'itemView' => '_view',
                        'emptyText' => 'Не найдено ни одного источника',
                        'summaryText' => 'Показаны источники с {start} до {end}, всего {count}',
                        'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
                    ]); ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="box">
            <div class="box-body">
                <?php echo CHtml::link('Добавить новый', $this->createUrl('create'), ['class' => 'btn btn-block btn-primary btn-sm']); ?>
            </div>
        </div>
    </div>
</div>
