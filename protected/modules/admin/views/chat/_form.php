<?php
/* @var $this ChatController */
/* @var $model Chat */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'chat-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lawyer_id'); ?>
		<?php echo $form->textField($model,'lawyer_id'); ?>
		<?php echo $form->error($model,'lawyer_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_payed'); ?>
		<?php echo $form->textField($model,'is_payed'); ?>
		<?php echo $form->error($model,'is_payed'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'transaction_id'); ?>
		<?php echo $form->textField($model,'transaction_id',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'transaction_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_closed'); ?>
		<?php echo $form->textField($model,'is_closed'); ?>
		<?php echo $form->error($model,'is_closed'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'chat_id'); ?>
		<?php echo $form->textField($model,'chat_id',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'chat_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'is_confirmed'); ?>
		<?php echo $form->textField($model,'is_confirmed'); ?>
		<?php echo $form->error($model,'is_confirmed'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->