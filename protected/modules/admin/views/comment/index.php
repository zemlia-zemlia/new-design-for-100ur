<?php
/* @var $this QuestionController */

use App\models\Comment;
use App\models\User;

/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('Отзывы.' . Yii::app()->name);
Yii::app()->clientScript->registerScriptFile('/js/admin/comments.js');

switch ($type) {
    case Comment::TYPE_ANSWER:
        $typeName = 'Комментарии';
        break;
    case Comment::TYPE_COMPANY:
        $typeName = 'Отзывы';
        break;
    default:
        $typeName = 'Комментарии';
}

$this->breadcrumbs = [
    $typeName,
];
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);

?>
<div class="vert-margin30">
    <h1><?php echo $typeName; ?>.

        <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
            <?php if (!is_null($status)): ?>
                <?php echo Comment::getStatusName($status); ?>
            <?php endif; ?>
        <?php endif; ?>
    </h1>
</div>

<div class="box">
    <div class="box-body">
        <table class="table table-bordered table-hover table-striped">
            <thead>
            <tr>
                <th>Текст</th>
                <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                    <th>Управление</th>
                <?php endif; ?>
            </tr>
            </thead>
            <?php $this->widget('zii.widgets.CListView', [
                'dataProvider' => $dataProvider,
                'itemView' => '_view',
                'emptyText' => 'Не найдено ни одного комментария',
                'summaryText' => 'Показаны ' . $typeName . ' с {start} до {end}, всего {count}',
                'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
            ]); ?>
        </table>
    </div>
</div>