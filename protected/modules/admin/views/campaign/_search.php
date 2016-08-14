<?php
/* @var $this CampaignController */
/* @var $model Campaign */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'regionId'); ?>
		<?php echo $form->textField($model,'regionId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'townId'); ?>
		<?php echo $form->textField($model,'townId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'timeFrom'); ?>
		<?php echo $form->textField($model,'timeFrom'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'timeTo'); ?>
		<?php echo $form->textField($model,'timeTo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'price'); ?>
		<?php echo $form->textField($model,'price'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'balance'); ?>
		<?php echo $form->textField($model,'balance'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'leadsDayLimit'); ?>
		<?php echo $form->textField($model,'leadsDayLimit'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'brakPercent'); ?>
		<?php echo $form->textField($model,'brakPercent'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'buyerId'); ?>
		<?php echo $form->textField($model,'buyerId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'active'); ?>
		<?php echo $form->textField($model,'active'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->