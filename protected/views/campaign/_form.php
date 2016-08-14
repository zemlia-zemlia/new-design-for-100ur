<?php
/* @var $this CampaignController */
/* @var $model Campaign */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'campaign-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'region'); ?>
                <?php echo $form->dropDownList($model,'regionId', $regions, array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'regionId'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'town'); ?>
                <?php echo CHtml::textField('town', isset($model->town->name)?$model->town->name:"", array(
                    'id'            =>  'town-selector', 
                    'class'         =>  'form-control',
                    'data-toggle'   =>  "tooltip",
                    'data-placement'=>  "bottom",
                    'title'         =>  "При указании города Вы будете получать лиды только из этого города",
                )); ?>
                <?php
                    echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
                ?>
		<?php echo $form->error($model,'townId'); ?>
	</div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                            <?php echo $form->labelEx($model,'timeFrom'); ?>
                            <div class="input-group">
                            <?php echo $form->textField($model,'timeFrom', array('class'=>'form-control')); ?>
                            <span class="input-group-addon">ч.</span>
                            </div>
                            <?php echo $form->error($model,'timeFrom'); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                            <?php echo $form->labelEx($model,'timeTo'); ?>
                            <div class="input-group">
                            <?php echo $form->textField($model,'timeTo', array('class'=>'form-control')); ?>
                            <span class="input-group-addon">ч.</span>
                            </div>
                            <?php echo $form->error($model,'timeTo'); ?>
                    </div>
                </div>
            </div>
	

	<div class="form-group">
		<?php echo $form->labelEx($model,'price'); ?>
                <div class="input-group">
		<?php echo $form->textField($model,'price', array('class'=>'form-control')); ?>
                <span class="input-group-addon">руб.</span>
                </div>
		<?php echo $form->error($model,'price'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'balance'); ?>
                <div class="input-group">
		<?php echo $form->textField($model,'balance', array('class'=>'form-control')); ?>
                <span class="input-group-addon">руб.</span>
                </div>
		<?php echo $form->error($model,'balance'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'leadsDayLimit'); ?>
		<?php echo $form->textField($model,'leadsDayLimit', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'leadsDayLimit'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'brakPercent'); ?>
		<?php echo $form->textField($model,'brakPercent', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'brakPercent'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'buyerId'); ?>
                <?php echo $form->dropDownList($model,'buyerId', $buyersArray, array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'buyerId'); ?>
	</div>

	<div class="checkbox">
            <label>
                <?php echo $form->checkBox($model,'active'); ?>
		<?php echo $model->getAttributeLabel('active'); ?>
            </label>
        </div>

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->