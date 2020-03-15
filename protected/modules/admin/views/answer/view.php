<?php
/* @var $this QuestionController */

use App\helpers\DateHelper;

/* @var $model Question */

$this->setPageTitle(CHtml::encode($model->id) . '. Ответы. ' . Yii::app()->name);

$this->breadcrumbs = [
    'Ответы' => ['index'],
    $model->id,
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('CRM', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);

?>
<h1>Ответ #<?php echo $model->id; ?></h1>

<p>
    <?php echo nl2br(CHtml::encode($model->answerText)); ?>
</p>

<?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)):?> 
<div class="">
    <p><strong>Статус:</strong> <?php echo CHtml::encode($model->getAnswerStatusName()); ?>
        <span class="muted"><?php echo DateHelper::niceDate($model->datetime) . ' ' . CHtml::encode($model->author->name . ' ' . $model->author->lastName); ?></span>
    </p>
    
    <p><strong>Автор:</strong> <?php echo CHtml::encode($model->author->lastName . ' ' . $model->author->name); ?></p>
</div>

<?php echo CHtml::link('Редактировать ответ', Yii::app()->createUrl('/admin/answer/update', ['id' => $model->id]), ['class' => 'btn btn-primary']); ?>

<?php endif; ?>