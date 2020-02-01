<?php
/* @var $this UserStatusRequestController */
/* @var $model UserStatusRequest */
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
		<?php echo $form->label($model, 'yuristId'); ?>
		<?php echo $form->textField($model, 'yuristId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'status'); ?>
		<?php echo $form->textField($model, 'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'isVerified'); ?>
		<?php echo $form->textField($model, 'isVerified'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'vuz'); ?>
		<?php echo $form->textField($model, 'vuz', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'facultet'); ?>
		<?php echo $form->textField($model, 'facultet', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'education'); ?>
		<?php echo $form->textField($model, 'education', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'vuzTownId'); ?>
		<?php echo $form->textField($model, 'vuzTownId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'educationYear'); ?>
		<?php echo $form->textField($model, 'educationYear'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'advOrganisation'); ?>
		<?php echo $form->textField($model, 'advOrganisation', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'advNumber'); ?>
		<?php echo $form->textField($model, 'advNumber', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'position'); ?>
		<?php echo $form->textField($model, 'position', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->