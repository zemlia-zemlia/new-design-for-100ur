<?php
/* @var $this FileCategoryController */
/* @var $model FileCategory */
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
		<?php echo $form->label($model, 'lft'); ?>
		<?php echo $form->textField($model, 'lft'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'rgt'); ?>
		<?php echo $form->textField($model, 'rgt'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'root'); ?>
		<?php echo $form->textField($model, 'root'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'level'); ?>
		<?php echo $form->textField($model, 'level'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->