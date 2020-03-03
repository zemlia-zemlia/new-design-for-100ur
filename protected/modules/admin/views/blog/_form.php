<?php
/* @var $this CategoryController */
/* @var $model Postcategory */
/* @var $form CActiveForm */
?>

<div>

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'postcategory-form',
    'enableAjaxValidation' => false,
]); ?>

    <p class="note"><span class="required">*</span> обязательные поля</p>

    <?php echo $form->errorSummary($model); ?>

<div class="form-group">
    <?php echo $form->labelEx($model, 'title'); ?>
    <?php echo $form->textField($model, 'title', ['class' => 'form-control', 'maxlength' => 256]); ?>
    <?php echo $form->error($model, 'title'); ?>
</div>
    
<div class="form-group">
    <?php echo $form->labelEx($model, 'description'); ?>
    <?php echo $form->textArea($model, 'description', ['rows' => 6, 'class' => 'form-control']); ?>
    <?php echo $form->error($model, 'description'); ?>
</div>
        
<div class="form-group">    
    <?php echo $form->labelEx($model, 'alias'); ?>
    <?php echo $form->textField($model, 'alias', ['class' => 'form-control', 'maxlength' => 256]); ?>
    <?php echo $form->error($model, 'alias'); ?>
</div>    
<br />

        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать категорию' : 'Сохранить категорию', ['class' => 'btn btn-large btn-primary']); ?>

<?php $this->endWidget(); ?>

</div><!-- form -->