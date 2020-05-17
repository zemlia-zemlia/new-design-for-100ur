<?php
/* @var $this ChatController */
/* @var $model Chat */
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
		<?php echo $form->label($model, 'user_id'); ?>
		<?php echo $form->textField($model, 'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'lawyer_id'); ?>
		<?php echo $form->textField($model, 'lawyer_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'is_payed'); ?>
		<?php echo $form->textField($model, 'is_payed'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'transaction_id'); ?>
		<?php echo $form->textField($model, 'transaction_id', ['size' => 60, 'maxlength' => 255]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'created'); ?>
		<?php echo $form->textField($model, 'created'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'is_closed'); ?>
		<?php echo $form->textField($model, 'is_closed'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'chat_id'); ?>
		<?php echo $form->textField($model, 'chat_id', ['size' => 60, 'maxlength' => 255]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'is_confirmed'); ?>
		<?php echo $form->textField($model, 'is_confirmed'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->