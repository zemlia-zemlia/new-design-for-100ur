<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $form CActiveForm */
?>

<div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'                    =>  'question-form',
	'enableAjaxValidation'  =>  false,
        'action'                =>  Yii::app()->createUrl('question/create'),
)); ?>

        <div class="form-group">
		<?php echo $form->labelEx($model,'questionText'); ?>
		<?php echo $form->textArea($model,'questionText', array('class'=>'form-control', 'rows'=>4)); ?>
		<?php echo $form->error($model,'questionText'); ?>
	</div>

        <div class="form-group">
                <label>Ваше имя *</label>
		<?php echo $form->textField($model,'authorName', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'authorName'); ?>
	</div>

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Задать вопрос юристу' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->