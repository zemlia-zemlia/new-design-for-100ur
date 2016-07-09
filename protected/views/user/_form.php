<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerScriptFile('/js/user.js');

?>

<div class="container-fluid">
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'   =>  array(
            'class'     =>  'login-form',
            'enctype'   =>  'multipart/form-data',
            ),
)); ?>


	<?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>
        <?php echo $form->errorSummary($yuristSettings, "Исправьте ошибки"); ?>
        
<div class="form-group radio-labels">
        <strong>Выберите подходящий Вам тип аккаунта</strong><br />
        <?php // echo $form->radioButtonList($model,'role', $rolesNames, array('class'=>'form-control'));?>
        <div class="alert alert-info">
            <?php echo $form->radioButton($model,'role', array('class'=>'form-control', 'value'=>User::ROLE_CLIENT, 'id'=>'role_client'));?>
            <label for="role_client"><strong>Клиент.</strong> Вам подойдет этот тип аккаунта, если Вы хотите получить юридическую помощь</label><br />
        </div>
        <div class="alert alert-info">
            <?php echo $form->radioButton($model,'role', array('class'=>'form-control', 'value'=>User::ROLE_JURIST, 'id'=>'role_yurist'));?>
            <label for="role_yurist"><strong>Юрист.</strong> Для специалистов в области права.</label>
        </div>
        <?php echo $form->error($model,'role'); ?>
</div>
        
<?php if(Yii::app()->user->checkAccess(User::ROLE_MANAGER) || $model->scenario!='update'):?>       
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <?php echo $form->labelEx($model,'name'); ?>
            <?php echo $form->textField($model,'name', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'name'); ?>
        </div> 

        <div class="yurist-fields">
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
        </div>
</div>
</div>      

       
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->textField($model,'email', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'email'); ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <?php echo $form->labelEx($model,'phone'); ?>
            <?php echo $form->textField($model,'phone', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'phone'); ?>
        </div>
    </div>
</div>
    
<div class="row">
    <div class="col-sm-12"> 
        <div class="form-group">
            <?php echo $form->labelEx($model,'townId'); ?>
            <?php echo CHtml::textField('town', '', array('id'=>'town-selector', 'class'=>'form-control')); ?>
            <?php
                echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
            ?>
        </div>
    </div>
</div>
<?php endif;?>
        
<?php if($model->isNewRecord == false):?>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <?php echo $form->labelEx($model,'password'); ?>
            <?php echo $form->passwordField($model,'password', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <?php echo $form->labelEx($model,'password2'); ?>
            <?php echo $form->passwordField($model,'password2', array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'password2'); ?>
        </div> 
    </div>
</div>
    <p>
        <?php echo CHtml::link('Изменить пароль', Yii::app()->createUrl('user/changePassword', array('id'=>$model->id)), array('class'=>'btn btn-warning'));?>
    </p>  
         
<?php endif;?>
        
        
<?php if(Yii::app()->user->checkAccess(User::ROLE_MANAGER)):?> 
    <div class="form-group">
            <?php echo $form->checkBox($model,'active'); ?>
            <?php echo $model->getAttributeLabel('active');?>
            <?php echo $form->error($model,'active'); ?>
    </div> 
<?php endif;?>

<?php if($model->isNewRecord == false):?>
    <div class="form-group">
        <?php echo $form->labelEx($model,'avatarFile'); ?>
        <?php echo $form->fileField($model, 'avatarFile');?>
        <?php echo $form->error($model,'avatarFile'); ?>
    </div> 
<?php endif;?>
           
        
        <div class="yurist-fields">
    <?php if($model->role == User::ROLE_JURIST && !$model->isNewRecord):?>
        <h3>Настройки юриста</h3>
        
<div class="row">
    <div class="col-sm-12"> 
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
        
        <?php echo $form->hiddenField($yuristSettings,'townId', array('class'=>'form-control', 'value'=>$model->townId)); ?>
                
        <div class="form-group"> 
            <?php echo $form->labelEx($yuristSettings,'description'); ?>
            <?php echo $form->textArea($yuristSettings, 'description', array('class'=>'form-control', 'rows'=>3));?>
            <?php echo $form->error($yuristSettings,'description'); ?>
        </div>
    </div>
</div>
    <?php endif;?>
        </div>
     
        
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Зарегистрироваться' : 'Сохранить', array('class'=>'btn btn-primary btn-lg')); ?>
        </div>
    </div>
</div>    

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>