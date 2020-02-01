<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'question-category-form',
    'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>

	<div class="form-group">
            <?php echo $form->labelEx($model, 'name'); ?>
            <?php echo $form->textField($model, 'name', array('class'=>'form-control')); ?>
            <?php echo $form->error($model, 'name'); ?>
	</div>

	<div class="form-group">
            <?php echo $form->labelEx($model, 'parentId'); ?>
            <?php echo $form->dropDownList($model, 'parentId', QuestionCategory::getCategoriesIdsNames(), array('class'=>'form-control')); ?>
            <?php echo $form->error($model, 'parentId'); ?>
	</div>

	<div class="row buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->