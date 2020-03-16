<?php
/* @var $this CommentController */

use App\models\Comment;

/* @var $model Comment */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', [
        'id' => 'comment-form',
        'enableAjaxValidation' => false,
    ]); ?>


    <?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>

    <?php if (Yii::app()->user->isGuest): ?>
        <div class="form-group row">
            <div class="col-sm-4">
                <strong>Ваше имя:</strong>
                <?php echo $form->textField($model, 'authorName', ['class' => 'form-control']); ?>
                <?php echo $form->error($model, 'authorName'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (true === $showTitle): ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'title'); ?>
            <?php echo $form->textField($model, 'title', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'title'); ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?php echo $form->textArea($model, 'text', ['rows' => 6, 'class' => 'form-control']); ?>
        <?php echo $form->error($model, 'text'); ?>
    </div>

    <?php if (!$hideRating): ?>
        <div class="form-group">
            <strong>Поставьте оценку</strong>
            <?php $this->widget('CStarRating', ['name' => 'Comment[rating]', 'value' => $model->rating, 'maxRating' => 5]); ?>
        </div>
    <?php endif; ?>

    <?php echo $form->hiddenField($model, 'type', ['value' => $type]); ?>
    <?php echo $form->hiddenField($model, 'objectId', ['value' => $objectId]); ?>
    <?php echo $form->hiddenField($model, 'parentId', ['value' => $parentId]); ?>


    <div class="form-group">
        <?php echo CHtml::submitButton(isset($buttonText) ? $buttonText : 'Ответить', ['class' => 'yellow-button center-block']); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->