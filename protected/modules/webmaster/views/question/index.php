<?php
/* @var $this QuestionController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('Вопросы.' . Yii::app()->name);
Yii::app()->clientScript->registerScriptFile('/js/question.js');
Yii::app()->clientScript->registerScriptFile('/js/admin/question.js');

$this->breadcrumbs = [
    'Вопросы',
];
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('Кабинет вебмастера', '/webmaster/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);
?>
<div class="vert-margin30">
<h1>Вопросы</h1>
    
<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Вопрос</th>
        <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
            <th>Категория</th>
        <?php endif; ?>   
        <?php if (Yii::app()->user->checkAccess(User::ROLE_EDITOR)):?>
            <th>Автор</th>
        <?php endif; ?>
    </tr>
    </thead>
<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
        'emptyText' => 'Не найдено ни одного вопроса',
        'summaryText' => 'Показаны вопросы с {start} до {end}, всего {count}',
        'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
        'viewData' => [
            'allDirections' => $allDirections,
            'nocat' => $nocat,
        ],
]); ?>
</table>
