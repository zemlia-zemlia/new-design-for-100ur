<?php
/* @var $this LeadController */
/* @var $model Lead100 */
/* @var $form CActiveForm */
?>

<div class="form new-lead-form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'                    =>  'lead-form',
	'enableAjaxValidation'  =>  false,
        'action'                =>  ($action!='')?$action:'',
)); ?>

	<?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>

        <div class="form-group">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>60,'maxlength'=>255, 'class'=>'form-control phone-mask')); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>
        
        <?php if($model->sourceId && $model->isNewRecord):?>
            <?php echo $form->hiddenField($model,'sourceId'); ?>
        <?php else:?>
            <div class="form-group">
                <?php echo $form->labelEx($model,'sourceId'); ?>
                <?php echo $form->dropDownList($model,'sourceId', Leadsource::getSourcesArrayByUser(Yii::app()->user->id), array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'sourceId'); ?>
            </div>
        <?php endif;?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'question'); ?>
		<?php echo $form->textArea($model,'question',array('rows'=>6, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'question'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'town'); ?>
                <?php echo CHtml::textField('town', $model->town->name, array(
                    'id'            =>  'town-selector', 
                    'class'         =>  'form-control',
                )); ?>
                <?php
                    echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
                ?>
		<?php echo $form->error($model,'townId'); ?>
	</div>

        <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class'=>'btn btn-primary btn-block')); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->