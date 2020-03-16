<?php
/* @var $this QuestionCategoryController */

use App\models\QuestionCategory;

/* @var $model QuestionCategory */
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
		<?php echo $form->textField($model, 'name', ['size' => 60, 'maxlength' => 255]); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'parentId'); ?>
		<?php echo $form->textField($model, 'parentId'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->