<?php
/* @var $this CodecsController */
/* @var $model Codecs */
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
		<?php echo $form->label($model, 'pagetitle'); ?>
		<?php echo $form->textField($model, 'pagetitle', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'longtitle'); ?>
		<?php echo $form->textField($model, 'longtitle', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'description'); ?>
		<?php echo $form->textField($model, 'description', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'alias'); ?>
		<?php echo $form->textField($model, 'alias', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'parent'); ?>
		<?php echo $form->textField($model, 'parent'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'isfolder'); ?>
		<?php echo $form->textField($model, 'isfolder'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'introtext'); ?>
		<?php echo $form->textArea($model, 'introtext', array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'content'); ?>
		<?php echo $form->textArea($model, 'content', array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'menutitle'); ?>
		<?php echo $form->textField($model, 'menutitle', array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->