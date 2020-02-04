<?php
/* @var $this DocsController */
/* @var $model Docs */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'docs-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>



	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
    <div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textArea($model,'description'); ?>
        <?php echo $form->error($model,'description'); ?>
    </div>

	<div class="row">
      <div class="form-group">
		<?php echo $form->labelEx($model,'filename'); ?>

		<?php echo $form->fileField($model,'filename',array('size'=>60,'maxlength'=>255, 'class' => 'form-control')); ?>
		<?php echo $form->error($model,'filename'); ?>
      </div>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->textField($model,'type', ['class' => 'form-control']); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
        <p>Количество скачиваний: <?= $model->downloads_count ?></p>
<!--		--><?php //echo $form->labelEx($model,'downloads_count'); ?>
<!--		--><?php //echo $form->textField($model,'downloads_count',['class' => 'form-control']); ?>
<!--		--><?php //echo $form->error($model,'downloads_count'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Сохранить',['class' => 'btn btn-primary']); ?>
        <?php if (!$model->isNewRecord): ?>
        <a href="/docs/delete/?id=<?= $model->id ?>" class="btn btn-warning">Удалить</a>
        <?php endif; ?>

	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->