<?php
/* @var $this LeadController */
/* @var $model Lead */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form = $this->beginWidget('CActiveForm', [
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
]); ?>

	<div class="row">
		<?php echo $form->label($model, 'id'); ?>
		<?php echo $form->textField($model, 'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'name'); ?>
		<?php echo $form->textField($model, 'name', ['size' => 60, 'maxlength' => 255]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'phone'); ?>
		<?php echo $form->textField($model, 'phone', ['size' => 60, 'maxlength' => 255]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'sourceId'); ?>
		<?php echo $form->textField($model, 'sourceId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'question'); ?>
		<?php echo $form->textArea($model, 'question', ['rows' => 6, 'cols' => 50]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'question_date'); ?>
		<?php echo $form->textField($model, 'question_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'townId'); ?>
		<?php echo $form->textField($model, 'townId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'leadStatus'); ?>
		<?php echo $form->textField($model, 'leadStatus'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->