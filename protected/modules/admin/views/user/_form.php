<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'   =>  array(
            'class'     =>  'login-form',
            'enctype'   =>  'multipart/form-data',
            ),
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>
        <?php echo $form->errorSummary($yuristSettings, "Исправьте ошибки"); ?>
        

<?php if(Yii::app()->user->checkAccess(User::ROLE_MANAGER) || $model->scenario!='update'):?>       
<div class="form-group">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name', array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'name'); ?>
</div> 
        
<div class="form-group">
        <?php echo $form->labelEx($model,'name2'); ?>
        <?php echo $form->textField($model,'name2', array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'name2'); ?>
</div> 
        
<div class="form-group">
        <?php echo $form->labelEx($model,'lastName'); ?>
        <?php echo $form->textField($model,'lastName', array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'lastName'); ?>
</div> 

<div class="form-group">
        <?php echo $form->labelEx($model,'role'); ?>
        <?php echo $form->dropDownList($model,'role', $rolesNames, array('class'=>'form-control'));?>
        <?php echo $form->error($model,'role'); ?>
</div>
<div class="form-group">
        <?php echo $form->labelEx($model,'position'); ?>
        <?php echo $form->textField($model,'position', array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'position'); ?>
</div>    
<div class="form-group">
        <?php echo $form->labelEx($model,'email'); ?>
        <?php echo $form->textField($model,'email', array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'email'); ?>
</div>
<div class="form-group">
        <?php echo $form->labelEx($model,'phone'); ?>
        <?php echo $form->textField($model,'phone', array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'phone'); ?>
</div>
<?php endif;?>
        
<?php if($model->isNewRecord == true):?>
<div class="form-group">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password', array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'password'); ?>
</div>
<div class="form-group">
        <?php echo $form->labelEx($model,'password2'); ?>
        <?php echo $form->passwordField($model,'password2', array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'password2'); ?>
</div> 
<?php else:?>        
        <p>
            <?php echo CHtml::link('Изменить пароль', Yii::app()->createUrl('user/changePassword', array('id'=>$model->id)), array('class'=>'btn btn-warning'));?>
        </p>   
<?php endif;?>
        
        
        <?php if(Yii::app()->user->checkAccess(User::ROLE_MANAGER) || $model->scenario!='update'):?> 
        <div class="form-group">
                <?php echo $form->checkBox($model,'active'); ?>
                <?php echo $model->getAttributeLabel('active');?>
                <?php echo $form->error($model,'active'); ?>
        </div> 
        <?php endif;?>

<div class="form-group">
        <?php echo $form->labelEx($model,'avatarFile'); ?>
        <?php echo $form->fileField($model, 'avatarFile');?>
        <?php echo $form->error($model,'avatarFile'); ?>
</div> 
        
<div class="form-group">
       <?php echo $form->labelEx($model,'town'); ?>
       <?php echo CHtml::textField('town', '', array(
           'id'            =>  'town-selector', 
           'class'         =>  'form-control',
       )); ?>
       <?php
           echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
       ?>
       <?php echo $form->error($model,'townId'); ?>
</div>
         

<?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>        
       
<div class="form-group">
    <?php echo $form->checkBox($model,'viewLeads'); ?>
    <?php echo $model->getAttributeLabel('viewLeads');?>
    <?php echo $form->error($model,'viewLeads'); ?>
</div> 
<?php endif;?>
        
<div class="form-group">
    <?php echo $form->labelEx($model,'birthday'); ?>
    <?php $this->widget('zii.widgets.jui.CJuiDatePicker',
           array(
           'name'=>"User[birthday]",
           'value'=>$model['birthday'],
           'language'=>'ru',
           'options' => array(
               'dateFormat'=>'yy-mm-dd',
               'changeMonth'    =>  true,
               'changeYear'     =>  true,
               'yearRange'      =>  '1960:2000',
               
                            ),
           'htmlOptions' => array(
               'style'=>'text-align:right;',
               'class'=>'form-control'
               )    
           )
          );
    ?>
    <?php echo $form->error($model,'birthday'); ?>
</div>
        
    <?php if($model->role == User::ROLE_JURIST || $model->isNewRecord):?>
        <h3>Настройки юриста</h3>
        <div class="form-group">
                <?php echo $form->labelEx($yuristSettings,'alias'); ?>
                <?php echo $form->textField($yuristSettings,'alias', array('class'=>'form-control')); ?>
                <?php echo $form->error($yuristSettings,'alias'); ?>
        </div>
        <div class="form-group">
                <?php echo $form->labelEx($yuristSettings,'startYear'); ?>
                <?php echo $form->textField($yuristSettings,'startYear', array('class'=>'form-control')); ?>
                <?php echo $form->error($yuristSettings,'startYear'); ?>
        </div>
        <div class="form-group">
                <?php echo $form->labelEx($yuristSettings,'town'); ?>
		<?php echo $form->dropDownList($yuristSettings,'townId', $townsArray, array('class'=>'form-control')); ?>
                <?php echo $form->error($yuristSettings,'town'); ?>
        </div>
        <div class="form-group"> 
            <?php echo $form->labelEx($yuristSettings,'description'); ?>
            <?php echo $form->textArea($yuristSettings, 'description', array('class'=>'form-control', 'rows'=>3));?>
            <?php echo $form->error($yuristSettings,'description'); ?>
        </div>
    <?php endif;?>
        
<div class="form-group">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить пользователя' : 'Сохранить', array('class'=>'btn btn-primary btn-lg')); ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->