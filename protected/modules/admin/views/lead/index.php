<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = 'Лиды. ' . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile('/js/ .js');
Yii::app()->clientScript->registerScriptFile('/js/admin/lead.js');

$this->breadcrumbs = [
    'Лиды',
];

?>
<div class="vert-margin10">
    <?php $this->renderPartial('_searchForm', ['model' => $searchModel]); ?>
</div>

<div class="vert-margin10">
    <h1>Лиды
        <?php if (User::ROLE_ROOT == Yii::app()->user->role): ?>
            <?php echo CHtml::link('Разобрать лиды', Yii::app()->createUrl('/admin/lead/sendLeads'), ['class' => 'btn btn-xs btn-primary']); ?>
        <?php endif; ?>

        <?php echo CHtml::link('Добавить новый', Yii::app()->createUrl('/admin/lead/create/'), ['class' => 'btn btn-info btn-xs']); ?>

    </h1>

    <?php if (User::ROLE_ROOT == Yii::app()->user->role && YII_DEBUG === true): ?>
        <?php echo CHtml::link('Сгенерировать тестовых лидов', Yii::app()->createUrl('/admin/lead/generate')); ?>
    <?php endif; ?>
</div>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'template' => '{summary}{pager}{items}{pager}',
    'emptyText' => 'Не найдено ни одного лида',
    'summaryText' => 'Показаны лиды с {start} до {end}, всего {count}',
    'pager' => ['class' => 'GTLinkPager'],
    'ajaxUpdate' => false,
]); ?>
