<?php
/* @var $this TownController */
/* @var $model Town */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'town-form',
    'enableAjaxValidation' => false,
]); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'name'); ?>
		<?php echo $form->textArea($model, 'name', ['rows' => 6, 'cols' => 50]); ?>
		<?php echo $form->error($model, 'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'description'); ?>
		<?php echo $form->textArea($model, 'description', ['rows' => 6, 'cols' => 50]); ?>
		<?php echo $form->error($model, 'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'alias'); ?>
		<?php echo $form->textField($model, 'alias', ['size' => 60, 'maxlength' => 64]); ?>
		<?php echo $form->error($model, 'alias'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'size'); ?>
		<?php echo $form->textField($model, 'size'); ?>
		<?php echo $form->error($model, 'size'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->