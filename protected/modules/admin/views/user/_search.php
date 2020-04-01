<?php
/* @var $this UserController */

use App\models\User;

/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form = $this->beginWidget('CActiveForm', [
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
]); ?>

	<div class="row">
		<?php echo $form->label($model, 'id'); ?>
		<?php echo $form->textField($model, 'id', ['size' => 10, 'maxlength' => 10]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'name'); ?>
		<?php echo $form->textField($model, 'name', ['size' => 60, 'maxlength' => 255]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'role'); ?>
		<?php echo $form->textField($model, 'role'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'position'); ?>
		<?php echo $form->textField($model, 'position', ['size' => 60, 'maxlength' => 255]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'email'); ?>
		<?php echo $form->textField($model, 'email', ['size' => 60, 'maxlength' => 255]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'phone'); ?>
		<?php echo $form->textField($model, 'phone', ['size' => 60, 'maxlength' => 255]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'active'); ?>
		<?php echo $form->textField($model, 'active'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'manager'); ?>
		<?php echo $form->textField($model, 'manager', ['size' => 10, 'maxlength' => 10]); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->