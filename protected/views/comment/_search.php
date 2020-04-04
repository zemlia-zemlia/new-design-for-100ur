<?php
/* @var $this CommentController */

use App\models\Comment;

/* @var $model Comment */
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
		<?php echo $form->label($model, 'type'); ?>
		<?php echo $form->textField($model, 'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'authorId'); ?>
		<?php echo $form->textField($model, 'authorId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'objectId'); ?>
		<?php echo $form->textField($model, 'objectId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'text'); ?>
		<?php echo $form->textArea($model, 'text', ['rows' => 6, 'cols' => 50]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'dateTime'); ?>
		<?php echo $form->textField($model, 'dateTime'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->