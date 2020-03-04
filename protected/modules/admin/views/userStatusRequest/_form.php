<?php
/* @var $this UserStatusRequestController */
/* @var $model UserStatusRequest */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'user-status-request-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
]); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'yuristId'); ?>
		<?php echo $form->textField($model, 'yuristId'); ?>
		<?php echo $form->error($model, 'yuristId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'status'); ?>
		<?php echo $form->textField($model, 'status'); ?>
		<?php echo $form->error($model, 'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'isVerified'); ?>
		<?php echo $form->textField($model, 'isVerified'); ?>
		<?php echo $form->error($model, 'isVerified'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'vuz'); ?>
		<?php echo $form->textField($model, 'vuz', ['size' => 60, 'maxlength' => 255]); ?>
		<?php echo $form->error($model, 'vuz'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'facultet'); ?>
		<?php echo $form->textField($model, 'facultet', ['size' => 60, 'maxlength' => 255]); ?>
		<?php echo $form->error($model, 'facultet'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'education'); ?>
		<?php echo $form->textField($model, 'education', ['size' => 60, 'maxlength' => 255]); ?>
		<?php echo $form->error($model, 'education'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'vuzTownId'); ?>
		<?php echo $form->textField($model, 'vuzTownId'); ?>
		<?php echo $form->error($model, 'vuzTownId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'educationYear'); ?>
		<?php echo $form->textField($model, 'educationYear'); ?>
		<?php echo $form->error($model, 'educationYear'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'advOrganisation'); ?>
		<?php echo $form->textField($model, 'advOrganisation', ['size' => 60, 'maxlength' => 255]); ?>
		<?php echo $form->error($model, 'advOrganisation'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'advNumber'); ?>
		<?php echo $form->textField($model, 'advNumber', ['size' => 60, 'maxlength' => 255]); ?>
		<?php echo $form->error($model, 'advNumber'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'position'); ?>
		<?php echo $form->textField($model, 'position', ['size' => 60, 'maxlength' => 255]); ?>
		<?php echo $form->error($model, 'position'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->