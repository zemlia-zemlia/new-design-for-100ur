<?php
/* @var $this AnswerController */

use App\models\Answer;
use App\models\User;

/* @var $model Answer */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', [
        'id' => 'answer-form',
        'enableAjaxValidation' => false,
    ]);
    ?>


    <p class="text-muted small center-align">
        <strong>ЗАПРЕЩЕНО!</strong> Размещение в тексте ответа или коментария рекламы, email, телефонов и т.д., а также,
        запрещается полное или частичное копирование текста ответов с других ресурсов.
    </p>
    <div class="form-group">
        <?php echo $form->textArea($model, 'answerText', ['rows' => 7, 'class' => 'form-control limited-text']); ?>
        <div class="chars-counter text-center"></div>
        <?php echo $form->error($model, 'answerText'); ?>
        <p class="text-muted small center-align">
            Минимальная длина ответа: <?php echo Answer::ANSWER_MIN_LENGTH; ?> символов<br />
            <strong>ДОПУСТИМО!</strong> Ссылаться в ответах, на контактные данные
            указанные в вашем профиле.</p>
    </div>

    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'videoLink'); ?>
            <?php echo $form->textField($model, 'videoLink', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'videoLink'); ?>
        </div>
    <?php endif; ?>


    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="form-group">
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Опубликовать ответ' : 'Сохранить', ['class' => 'yellow-button center-block btn-lg']); ?>
            </div>
        </div>
    </div>


    <?php $this->endWidget(); ?>

</div><!-- form -->