<h1>Восстановление пароля</h1>

<?php if(empty($message)): ?>
    
<div class="login-form">

<?php $form=$this->beginWidget('CActiveForm', array(

	'id'=>'restorepassword-form',

	'enableAjaxValidation'=>false,
        'htmlOptions'   =>  array(
                'class' =>  'login-form',
                ),

)); ?>

    <div class="form-group">
    <?php echo $form->labelEx($model,'email'); ?>

    <?php echo $form->textField($model,'email', array('class'=>'form-control')); ?>

    <?php echo $form->error($model,'email'); ?>
    </div>

    <div class="form-group">
    <?if(extension_loaded('gd')):?>
        <?php echo CHtml::activeLabelEx($model, 'verifyCode')?>
        <?php $this->widget('CCaptcha', array('clickableImage'=>true,'buttonLabel'=>'Показать другой код'))?><br />
        <? echo $form->textField($model, 'verifyCode', array('class'=>'form-control'))?>
    <?endif?>
    </div>
    <?php echo CHtml::submitButton('Выслать пароль', array('class'=>'btn btn-primary')); ?>


<?php $this->endWidget(); ?>
</div><!-- form -->
<?php else: ?>
                
<?php 
    if(isset($message)) print $message;
?>                
                
<?php endif; ?>

