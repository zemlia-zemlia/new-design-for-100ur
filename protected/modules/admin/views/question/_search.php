<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model, 'id'); ?>
		<?php echo $form->textField($model, 'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'number'); ?>
		<?php echo $form->textField($model, 'number'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'questionText'); ?>
		<?php echo $form->textArea($model, 'questionText', array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'categoryId'); ?>
		<?php echo $form->textField($model, 'categoryId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'categoryName'); ?>
		<?php echo $form->textField($model, 'categoryName', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->