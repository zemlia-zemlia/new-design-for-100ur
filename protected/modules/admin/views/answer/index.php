<?php
/* @var $this QuestionController */

use App\models\Answer;
use App\models\User;

/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('Ответы.' . Yii::app()->name);
Yii::app()->clientScript->registerScriptFile('/js/answer.js');

$this->breadcrumbs = [
    'Ответы',
];
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('CRM', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>
<h2>Ответы.
    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
        <?php if (!is_null($status)): ?>
            <?php echo Answer::getStatusName($status); ?>
        <?php endif; ?>
    <?php endif; ?>
</h2>



<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
    'emptyText' => 'Не найдено ни одного ответа',
    'summaryText' => 'Показаны ответы с {start} до {end}, всего {count}',
    'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
    'ajaxUpdate' => false,
]); ?>