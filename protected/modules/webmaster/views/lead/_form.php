<?php
/* @var $this LeadController */
/* @var $model Lead */
/* @var $form CActiveForm */
?>


<div class="form new-lead-form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'lead-form',
    'enableAjaxValidation' => false,
        'action' => ('' != $action) ? $action : '',
]); ?>

	<?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>
<div class="row">
	<div class="col-sm-6">
	    <div class="form-group">
			<?php echo $form->labelEx($model, 'name'); ?>
			<?php echo $form->textField($model, 'name', ['size' => 60, 'maxlength' => 255, 'class' => 'form-control']); ?>
			<?php echo $form->error($model, 'name'); ?>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<?php echo $form->labelEx($model, 'phone'); ?>
			<?php echo $form->textField($model, 'phone', ['size' => 60, 'maxlength' => 255, 'class' => 'form-control phone-mask']); ?>
			<?php echo $form->error($model, 'phone'); ?>
		</div>
	</div>
</div>  
<div class="row">
	<div class="col-sm-6">

        <?php if ($model->sourceId && $model->isNewRecord):?>
            <?php echo $form->hiddenField($model, 'sourceId'); ?>
        <?php else:?>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sourceId'); ?>
                <?php echo $form->dropDownList($model, 'sourceId', Leadsource::getSourcesArrayByUser(Yii::app()->user->id), ['class' => 'form-control']); ?>
                <?php echo $form->error($model, 'sourceId'); ?>
            </div>
        <?php endif; ?>

	</div>
</div>  

<div class="row">
	<div class="col-sm-8">
		<div class="form-group">
			<?php echo $form->labelEx($model, 'question'); ?>
			<?php echo $form->textArea($model, 'question', ['rows' => 6, 'class' => 'form-control']); ?>
			<?php echo $form->error($model, 'question'); ?>
		</div>
	</div>
</div>  
<div class="row">
	<div class="col-sm-6">
		<div class="form-group">
			<?php echo $form->labelEx($model, 'town'); ?>
	                <?php echo CHtml::textField('town', $model->town->name, [
                        'id' => 'town-selector',
                        'class' => 'form-control',
                    ]); ?>
	                <?php
                        echo $form->hiddenField($model, 'townId', ['id' => 'selected-town']);
                    ?>
			<?php echo $form->error($model, 'townId'); ?>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-8">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-primary btn-block btn-lg']); ?>
	</div>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
