<?php
/* @var $this LeadsourceController */
/* @var $activeSources array */
/* @var $inactiveSources array */

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

                    <?php $this->renderPartial('_viewArray', [
                        'sources' => $activeSources,
                    ]);
                    ?>
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

                    <?php $this->renderPartial('_viewArray', [
                        'sources' => $inactiveSources,
                    ]);
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>



