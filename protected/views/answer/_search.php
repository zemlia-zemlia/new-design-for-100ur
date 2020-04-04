<?php
/* @var $this AnswerController */

use App\models\Answer;

/* @var $model Answer */
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
		<?php echo $form->label($model, 'questionId'); ?>
		<?php echo $form->textField($model, 'questionId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'answerText'); ?>
		<?php echo $form->textArea($model, 'answerText', ['rows' => 6, 'cols' => 50]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'authorId'); ?>
		<?php echo $form->textField($model, 'authorId'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->