<?php $form=$this->beginWidget('CActiveForm', array(

	'id'=>'login-form',
        'action' => Yii::app()->createUrl('site/login'),
	'enableAjaxValidation'=>false,
        'htmlOptions'   =>  array(
            'class' =>  '',
        ),

)); ?>

<?
    if(!isset($model)) $model=new LoginForm;
?>

<div class="form-group">
    <?php echo $form->labelEx($model,'email'); ?>

    <?php echo $form->textField($model,'email', array('class'=>'form-control input-lg','placeholder'=>$model->getAttributeLabel('email'))); ?>

    <?php echo $form->error($model,'email'); ?>
</div>



<div class="form-group">
    <?php echo $form->labelEx($model,'password'); ?>

    <?php echo $form->passwordField($model,'password', array('class'=>'form-control input-lg')); ?>

    <?php echo $form->error($model,'password'); ?>
</div>

<div class="form-group">
    <?php echo $form->checkBox($model,'rememberMe'); ?>

    <?php echo $model->getAttributeLabel('rememberMe');?>

    <?php echo $form->error($model,'rememberMe'); ?>

</div>

   <?php echo CHtml::submitButton('Войти',array('class'=>'btn btn-primary btn-lg')); ?>
    
   <?php echo CHtml::link('Забыли пароль?', Yii::app()->createUrl('user/restorePassword'));?>



<?php $this->endWidget(); ?>
