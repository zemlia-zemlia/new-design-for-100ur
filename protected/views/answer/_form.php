<?php
/* @var $this AnswerController */
/* @var $model Answer */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'answer-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'answerText'); ?>
		<?php echo $form->textArea($model,'answerText',array('rows'=>6, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'answerText'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'authorId'); ?>
		<?php echo $form->dropDownList($model,'authorId',$allJurists, array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'authorId'); ?>
	</div>

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->