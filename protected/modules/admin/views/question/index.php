<?php
/* @var $this QuestionController */

use App\models\Question;
use App\models\User;

/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('Вопросы и ответы.' . Yii::app()->name);
Yii::app()->clientScript->registerScriptFile('/js/question.js');
Yii::app()->clientScript->registerScriptFile('/js/admin/question.js');

$this->breadcrumbs = [
    'Вопросы и ответы',
];
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/admin/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>
<div>
    <h3>Вопросы.
        <?php if ($nocat): ?>
            (без категории)
        <?php endif; ?>

        <?php if ($notown): ?>
            (без города)
        <?php endif; ?>

        <?php if ($moderator): ?>
            (Модератор <?php echo $moderator->getShortName(); ?>)
        <?php endif; ?>

        <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>

        <?php if (!is_null($status)): ?>

            <?php echo Question::getStatusName($status); ?>
            <?php if (Question::STATUS_MODERATED == $status): ?>
                <?php echo CHtml::link('Опубликовать все одобренные', Yii::app()->createUrl('question/publish'), ['class' => 'btn btn-success']); ?>
            <?php endif; ?>

        <?php endif; ?>
    </h3>

</div>
<?php endif; ?>


<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th>Вопрос</th>
        <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
            <th>Категория</th>
        <?php endif; ?>
        <?php if (Yii::app()->user->checkAccess(User::ROLE_EDITOR)): ?>
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
