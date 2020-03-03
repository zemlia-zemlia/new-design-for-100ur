<div class="form center-align">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'user-form',
    'enableAjaxValidation' => false,
        'htmlOptions' => [
            'class' => 'login-form',
            'enctype' => 'multipart/form-data',
            ],
]); ?>

	<?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="form-group">
                    <?php echo $form->labelEx($model, 'password'); ?>
                    <?php echo $form->passwordField($model, 'password', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'password'); ?>
            </div>
        </div>
    </div>    

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="form-group">
                    <?php echo $form->labelEx($model, 'password2'); ?>
                    <?php echo $form->passwordField($model, 'password2', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'password2'); ?>
            </div> 
        </div> 
    </div> 
        
<div class="form-group">
    <?php echo CHtml::submitButton('Сохранить', ['class' => 'yellow-button center-block']); ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->