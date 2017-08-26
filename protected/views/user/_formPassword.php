<div class="form center-align">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'   =>  array(
            'class'     =>  'login-form',
            'enctype'   =>  'multipart/form-data',
            ),
)); ?>

	<?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="form-group">
                    <?php echo $form->labelEx($model,'password'); ?>
                    <?php echo $form->passwordField($model,'password', array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'password'); ?>
            </div>
        </div>
    </div>    

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="form-group">
                    <?php echo $form->labelEx($model,'password2'); ?>
                    <?php echo $form->passwordField($model,'password2', array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'password2'); ?>
            </div> 
        </div> 
    </div> 
        
<div class="form-group">
    <?php echo CHtml::submitButton('Сохранить', array('class'=>'yellow-button center-block')); ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->