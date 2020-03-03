<div class="form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'user-form',
    'enableAjaxValidation' => false,
        'htmlOptions' => [
            'class' => 'login-form',
            'enctype' => 'multipart/form-data',
            ],
]); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>

<div class="form-group">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password', ['class' => 'form-control']); ?>
        <?php echo $form->error($model, 'password'); ?>
</div>
<div class="form-group">
        <?php echo $form->labelEx($model, 'password2'); ?>
        <?php echo $form->passwordField($model, 'password2', ['class' => 'form-control']); ?>
        <?php echo $form->error($model, 'password2'); ?>
</div> 
        
<div class="form-group">
    <?php echo CHtml::submitButton('Сохранить', ['class' => 'btn btn-primary btn-lg']); ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->