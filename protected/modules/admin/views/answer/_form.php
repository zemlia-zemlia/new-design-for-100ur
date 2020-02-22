<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $form CActiveForm */
?>

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'answer-form',
    'enableAjaxValidation' => false,
]); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>
       
<div class="form-group">
        <?php echo $form->labelEx($model, 'answerText'); ?>
        <?php echo $form->textArea($model, 'answerText', ['class' => 'form-control', 'rows' => 10]); ?>
        <?php echo $form->error($model, 'answerText'); ?>
</div>

<div class="form-group">
        <?php echo $form->labelEx($model, 'status'); ?><br />
        <?php echo $form->radioButtonList($model, 'status', Answer::getStatusesArray(), ['class' => '', 'separator' => '&nbsp;&nbsp;']); ?>
        <?php echo $form->error($model, 'status'); ?>
</div>     

<div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-primary btn-block btn-lg']); ?>
</div>


<?php $this->endWidget(); ?>
