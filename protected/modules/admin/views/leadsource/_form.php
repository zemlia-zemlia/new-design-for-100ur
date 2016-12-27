<?php
/* @var $this LeadsourceController */
/* @var $model Leadsource */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'leadsource-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>
        
        
        <div class="form-group">
		<?php echo $form->checkBox($model,'active',array()); ?>
                <?php echo $model->getAttributeLabel('active');?>
		<?php echo $form->error($model,'active'); ?>
	</div>
        
	<div class="form-group">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('class'=>'form-control','rows'=>'3')); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
        
        
        <div class="form-group">
            <?php echo $form->checkBox($model, 'noLead');?>
            <?php echo $model->getAttributeLabel('noLead'); ?>
            <?php echo $form->error($model, 'noLead'); ?>
        </div> 

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->