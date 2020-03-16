<?php
/* @var $this TransactionCampaignController */

use App\models\TransactionCampaign;

/* @var $model TransactionCampaign */
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
		<?php echo $form->label($model, 'campaignId'); ?>
		<?php echo $form->textField($model, 'campaignId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'time'); ?>
		<?php echo $form->textField($model, 'time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'sum'); ?>
		<?php echo $form->textField($model, 'sum'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model, 'description'); ?>
		<?php echo $form->textArea($model, 'description', ['rows' => 6, 'cols' => 50]); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->