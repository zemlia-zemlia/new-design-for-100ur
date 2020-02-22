<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Лиды. " . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile('/js/lead.js');
Yii::app()->clientScript->registerScriptFile('/js/admin/lead.js');

$this->breadcrumbs = array(
    'Лиды',
);

?>
<div class="vert-margin10">
    <?php $this->renderPartial('_searchForm', array('model' => $searchModel)); ?>
</div>

<div class="vert-margin10">
    <h1>Лиды
        <?php if (Yii::app()->user->role == User::ROLE_ROOT): ?>
            <?php echo CHtml::link('Разобрать лиды', Yii::app()->createUrl('/admin/lead/sendLeads'), array('class' => 'btn btn-xs btn-primary')); ?>
        <?php endif; ?>

        <?php echo CHtml::link('Добавить новый', Yii::app()->createUrl('/admin/lead/create/'), array('class' => 'btn btn-info btn-xs')); ?>

    </h1>

    <?php if (Yii::app()->user->role == User::ROLE_ROOT && YII_DEBUG === true): ?>
        <?php echo CHtml::link('Сгенерировать тестовых лидов', Yii::app()->createUrl('/admin/lead/generate')); ?>
    <?php endif; ?>
</div>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'template' => '{summary}{pager}{items}{pager}',
    'emptyText' => 'Не найдено ни одного лида',
    'summaryText' => 'Показаны лиды с {start} до {end}, всего {count}',
    'pager' => array('class' => 'GTLinkPager'),
    'ajaxUpdate' => false,
)); ?>
