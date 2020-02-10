<?php
/* @var $this DocsController */
/* @var $model Docs */
/* @var $form CActiveForm */
?>


<div class="box">
    <div class="box-body">
        <div class="form">

            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'docs-form',
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation' => false,
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
            )); ?>

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

            <div class="form-group">
                <?php echo $form->labelEx($model, 'file'); ?>

                <?php echo $form->fileField($model, 'file', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'file'); ?>
            </div>


            <div class="form-group">
                <p>Количество скачиваний: <?= $model->downloads_count ?></p>
            </div>

            <div class="form-group buttons">
                <?php echo CHtml::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
                <?php if (!$model->isNewRecord): ?>
                    <a href="/docs/delete/?id=<?= $model->id ?>" class="btn btn-warning">Удалить</a>
                <?php endif; ?>

            </div>

            <?php $this->endWidget(); ?>

        </div><!-- form -->
    </div>
</div>