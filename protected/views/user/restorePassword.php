<h1>Восстановление пароля</h1>

<div class="flat-panel inside">

<?php if(empty($message)): ?>
    
<div class="row">
    <div class="col-md-6 col-md-offset-3">

<?php $form=$this->beginWidget('CActiveForm', array(

	'id'=>'restorepassword-form',

	'enableAjaxValidation'=>false,

)); ?>




<div class="form-group">
    <?php echo $form->labelEx($model,'email'); ?>
    <?php echo $form->textField($model,'email', array('class'=>'form-control')); ?>
    <?php echo $form->error($model,'email'); ?>
</div>

<?if(extension_loaded('gd')):?>
    <div class="form-group">
        <?=CHtml::activeLabelEx($model, 'verifyCode')?>
        <br />
        <?$this->widget('CCaptcha', array('clickableImage'=>true,'buttonLabel'=>'Показать другой'))?><br />
        <? echo $form->textField($model, 'verifyCode', array('class'=>'form-control'))?>
    </div>
<?endif?>

<div class="form-group">
    <?php echo CHtml::submitButton('Выслать пароль', array('class'=>'btn btn-primary btn-block')); ?>
</div>

<?php $this->endWidget(); ?>
        </div>
</div><!-- row -->
<?php else: ?>
                
<?php 
    if(isset($message)) print $message;
?>                
                
<?php endif; ?>


</div>
