<?php
/* @var $this FileCategoryController */
/* @var $model FileCategory */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'file-category-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    )); ?>
    <div class="box">
        <div class="box-body">
            <?php echo $form->errorSummary($model); ?>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'name'); ?>
                <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'description'); ?>
                <?php echo $form->textArea($model, 'description', array('rows' => 6, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'description'); ?>
            </div>


            <div class="form-group buttons">
                <?php echo CHtml::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>

</div><!-- form -->