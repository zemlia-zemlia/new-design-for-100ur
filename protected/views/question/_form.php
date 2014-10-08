<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $form CActiveForm */
?>

<div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'question-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, "Для отправки вопроса укажите данные"); ?>
       
	<div class="form-group">
		<?php echo $form->labelEx($model,'questionText'); ?>
		<?php echo $form->textArea($model,'questionText', array('class'=>'form-control', 'rows'=>10)); ?>
		<?php echo $form->error($model,'questionText'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'category'); ?>
		<?php echo $form->dropDownList($model, 'categoryId', $allCategories, array('class'=>'form-control', 'options'=>array((is_null($categoryId)?0:$categoryId)=>array('selected'=>true)))); ?>
		<?php echo $form->error($model,'categoryId'); ?>
	</div>
        
        <div class="form-group">
		<?php echo $form->labelEx($model,'authorName'); ?>
		<?php echo $form->textField($model,'authorName', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'authorName'); ?>
	</div>
        
        <div class="form-group">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>
        
        <div class="form-group">
		<?php echo $form->labelEx($model,'town'); ?>
		<?php echo $form->dropDownList($model,'townId', $townsArray, array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'townId'); ?>
	</div>

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Задать вопрос' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->