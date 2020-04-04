<?php
/* @var $this QuestionCategoryController */

use App\models\QuestionCategory;

/* @var $model QuestionCategory */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'question-category-form',
    'enableAjaxValidation' => false,
]); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>

	<div class="form-group">
            <?php echo $form->labelEx($model, 'name'); ?>
            <?php echo $form->textField($model, 'name', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'name'); ?>
	</div>

	<div class="form-group">
            <?php echo $form->labelEx($model, 'parentId'); ?>
            <?php echo $form->dropDownList($model, 'parentId', QuestionCategory::getCategoriesIdsNames(), ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'parentId'); ?>
	</div>

	<div class="row buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-primary']); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->