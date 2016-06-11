<?php
/* @var $this LeadController */
/* @var $model Lead */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lead-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>

        <div class="form-group">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>
        
        <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
        <div class="form-group">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
        <?php endif;?>

        <div class="form-group">
		<?php echo $form->labelEx($model,'sourceId'); ?>
		<?php echo $form->dropDownList($model,'sourceId', Leadsource::getSourcesArray(false), array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'sourceId'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'question'); ?>
		<?php echo $form->textArea($model,'question',array('rows'=>6, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'question'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'townId'); ?>
		<?php echo $form->dropDownList($model,'townId', Town::getTownsIdsNames(), array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'townId'); ?>
	</div>
        
	<div class="form-group">
		<?php echo $form->labelEx($model,'leadStatus'); ?>
		<?php echo $form->dropDownList($model,'leadStatus', Lead::getLeadStatusesArray(), array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'leadStatus'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->