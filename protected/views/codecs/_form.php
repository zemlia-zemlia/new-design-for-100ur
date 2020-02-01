<?php
/* @var $this CodecsController */
/* @var $model Codecs */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'codecs-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model, 'pagetitle'); ?>
		<?php echo $form->textField($model, 'pagetitle', array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model, 'pagetitle'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'longtitle'); ?>
		<?php echo $form->textField($model, 'longtitle', array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model, 'longtitle'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'description'); ?>
		<?php echo $form->textField($model, 'description', array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model, 'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'alias'); ?>
		<?php echo $form->textField($model, 'alias', array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model, 'alias'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'parent'); ?>
		<?php echo $form->textField($model, 'parent'); ?>
		<?php echo $form->error($model, 'parent'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'isfolder'); ?>
		<?php echo $form->textField($model, 'isfolder'); ?>
		<?php echo $form->error($model, 'isfolder'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'introtext'); ?>
		<?php echo $form->textArea($model, 'introtext', array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model, 'introtext'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'content'); ?>
		<?php echo $form->textArea($model, 'content', array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model, 'content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model, 'menutitle'); ?>
		<?php echo $form->textField($model, 'menutitle', array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model, 'menutitle'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->