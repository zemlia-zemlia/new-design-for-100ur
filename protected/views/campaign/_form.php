<?php
/* @var $this CampaignController */
/* @var $model Campaign */
/* @var $form CActiveForm */
?>

<div class="">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'campaign-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'   =>  array('class' => 'form-horizontal'),
)); ?>

	<?php echo $form->errorSummary($model); ?>

        <p class="flat-panel inside">
            Выберите регион ИЛИ город, в котором хотите покупать лиды.
        </p>
    
	<div class="form-group">
            <?php echo $form->labelEx($model,'region', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-10 col-md-6">
                <?php echo $form->dropDownList($model,'regionId', $regions, array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'regionId'); ?>
            </div>
	</div>

	<div class="form-group">
            <?php echo $form->labelEx($model,'town', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-10 col-md-6">
                <?php echo CHtml::textField('town', isset($model->town->name)?$model->town->name:"", array(
                    'id'            =>  'town-selector', 
                    'class'         =>  'form-control',
                    'data-toggle'   =>  "tooltip",
                    'data-placement'=>  "bottom",
                    'title'         =>  "При указании города Вы будете получать лиды только из этого города",
                )); ?>
            </div>
                <?php
                    echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
                ?>
		<?php echo $form->error($model,'townId'); ?>
	</div>


        <p class="flat-panel inside">
            Укажите время, в которое хотите получать лиды. Если хотите покупать круглосуточно, укажите с 0 до 24.<br />
            Внимание: время указывается московское!
        </p>
        
        <div class="form-group">
                <?php echo $form->labelEx($model,'timeFrom', array('class' => 'col-sm-2 control-label')); ?>
                <div class="col-sm-10  col-md-2">    
                    <div class="input-group">
                    <?php echo $form->textField($model,'timeFrom', array('class'=>'form-control')); ?>
                    <span class="input-group-addon">ч.</span>
                    </div>
                </div>
                <?php echo $form->error($model,'timeFrom'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model,'timeTo', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-10 col-md-2">
                <div class="input-group">
                <?php echo $form->textField($model,'timeTo', array('class'=>'form-control')); ?>
                <span class="input-group-addon">ч.</span>
                </div>
            </div>
                <?php echo $form->error($model,'timeTo'); ?>
        </div> 

	<div class="form-group">
		<?php echo $form->labelEx($model,'leadsDayLimit', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-10 col-md-2">
		<?php echo $form->textField($model,'leadsDayLimit', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'leadsDayLimit'); ?>
            </div>
	</div>
        
<?php if(Yii::app()->user->role == User::ROLE_ROOT):?>
	<div class="form-group">
            <?php echo $form->labelEx($model,'brakPercent', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-10 col-md-2">
		<?php echo $form->textField($model,'brakPercent', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'brakPercent'); ?>
            </div>
	</div>
        
        <div class="form-group">
            <?php echo $form->labelEx($model,'price', array('class' => 'col-sm-2 control-label')); ?>
            <div class="col-sm-10 col-md-2">
                <div class="input-group">
                    <?php echo $form->textField($model,'price', array('class'=>'form-control')); ?>
                    <span class="input-group-addon">руб.</span>
                </div>
		<?php echo $form->error($model,'price'); ?>
            </div>
	</div>
        
 <?php endif;?>
        
        
    <?php if(!$model->isNewRecord):?>    
    <div class="form-group">
        <?php echo $form->labelEx($model,'active', array('class' => 'col-sm-2 control-label')); ?>
        <div class="col-sm-10 col-md-6">
            <?php if(Yii::app()->user->role == User::ROLE_ROOT):?>
                
                    <?php echo $form->dropDownList($model,'active', Campaign::getActivityStatuses(), array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'active'); ?>
                
            <?php else:?>
                <?php if($model->active != Campaign::ACTIVE_MODERATION):?>
                    <div class="checkbox">
                        <label>
                            <?php echo $form->checkBox($model,'active'); ?>
                            <?php echo $model->getAttributeLabel('active'); ?>
                        </label>
                    </div>
                <?php else:?>
                    <?php echo $model->getActiveStatusName();?>
                <?php endif;?>
            <?php endif;?>
        </div>
    </div>
    <?php endif;?>
        
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
                <label>
                    <?php echo $form->checkBox($model,'sendEmail'); ?>
                    <?php echo $model->getAttributeLabel('sendEmail'); ?>
                </label>
            </div>
        </div>
    </div>
    
	<div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
            </div>
        </div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
$("#Campaign_regionId").on('change', function(){
    if($(this).val() != 0) {
        $("#town-selector").val('').hide();
        $("#selected-town").val(0); 
    } else {
        $("#town-selector").show();
    }
})
$("#town-selector").on('change', function(){
    if($(this).val() != '') {
        $("#Campaign_regionId").val('Не выбран').hide();
    } else {
        $("#Campaign_regionId").show();
    }
})    
</script>