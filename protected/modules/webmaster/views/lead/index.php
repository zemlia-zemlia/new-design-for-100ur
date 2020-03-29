<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = 'Лиды. ' . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile('/js/lead.js');
Yii::app()->clientScript->registerScriptFile('/js/admin/lead.js');

$this->breadcrumbs = [
    'Лиды',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('Кабинет вебмастера', '/webmaster/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);

?>

<h1>Мои лиды

<?php

    if (Leadsource::getSourcesArrayByUser(Yii::app()->user->id) !== null) : ?>
    <?php echo CHtml::link('Добавить лид вручную', Yii::app()->createUrl('/webmaster/lead/create'), ['class' => 'btn btn-primary']); ?>
    <?php endif; ?>
</h1>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
        'emptyText' => 'Не найдено ни одного лида',
        'summaryText' => 'Показаны лиды с {start} до {end}, всего {count}',
        'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
]); ?>
