<?php
/* @var $this PostController */

use App\models\Post;

/* @var $model Post */
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
		<?php echo $form->label($model, 'authorId'); ?>
		<?php echo $form->textField($model, 'authorId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'title'); ?>
		<?php echo $form->textField($model, 'title', ['size' => 60, 'maxlength' => 256]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'text'); ?>
		<?php echo $form->textArea($model, 'text', ['rows' => 6, 'cols' => 50]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'datetime'); ?>
		<?php echo $form->textField($model, 'datetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'rating'); ?>
		<?php echo $form->textField($model, 'rating'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->