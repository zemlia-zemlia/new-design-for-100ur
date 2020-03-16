<?php
/* @var $this TownController */

use App\models\Town;

/* @var $model Town */
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
		<?php echo $form->textArea($model, 'name', ['rows' => 6, 'cols' => 50]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'description'); ?>
		<?php echo $form->textArea($model, 'description', ['rows' => 6, 'cols' => 50]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'alias'); ?>
		<?php echo $form->textField($model, 'alias', ['size' => 60, 'maxlength' => 64]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'size'); ?>
		<?php echo $form->textField($model, 'size'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'description1'); ?>
		<?php echo $form->textArea($model, 'description1', ['rows' => 6, 'cols' => 50]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'description2'); ?>
		<?php echo $form->textArea($model, 'description2', ['rows' => 6, 'cols' => 50]); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->