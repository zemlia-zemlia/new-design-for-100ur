<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Ответы." . Yii::app()->name);
Yii::app()->clientScript->registerScriptFile('/js/answer.js');


$this->breadcrumbs = array(
    'Ответы',
);
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('CRM', "/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));
?>
<h2>Ответы.
    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
        <?php if (!is_null($status)): ?>
            <?php echo Answer::getStatusName($status); ?>
        <?php endif; ?>
    <?php endif; ?>
</h2>

<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Ответ</th>
        <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
            <th>Автор</th>
        <?php endif; ?>
    </tr>
    </thead>
    <?php $this->widget('zii.widgets.CListView', array(
        'dataProvider' => $dataProvider,
        'itemView' => '_view',
        'emptyText' => 'Не найдено ни одного ответа',
        'summaryText' => 'Показаны ответы с {start} до {end}, всего {count}',
        'pager' => array('class' => 'GTLinkPager') //we use own pager with russian words

    )); ?>
</table>
