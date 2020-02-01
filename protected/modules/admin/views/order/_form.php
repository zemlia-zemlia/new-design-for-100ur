<?php
/* @var $this OrderController */
/* @var $model Order */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'order-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'status'); ?>
		<?php echo $form->dropDownList($model, 'status', Order::getStatusesArray(), ['class'=>'form-control']); ?>
		<?php echo $form->error($model, 'status'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'price'); ?>
		<?php echo $form->textField($model, 'price', ['class'=>'form-control']); ?>
		<?php echo $form->error($model, 'price'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'description'); ?>
		<?php echo $form->textArea($model, 'description', ['rows'=>6, 'class'=>'form-control']); ?>
		<?php echo $form->error($model, 'description'); ?>
	</div>

	<div class="form-group">
		<?php echo CHtml::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->