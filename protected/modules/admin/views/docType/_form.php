<?php
/* @var $this DocTypeController */

use App\models\DocType;

/* @var $model DocType */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'doc-type-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation' => false,
]); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, '<h4>Исправьте ошибки</h4>'); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'class'); ?>
		<?php echo $form->dropDownList($model, 'class', DocType::getClassesArray(), ['class' => 'form-control']); ?>
		<?php echo $form->error($model, 'class'); ?>
	</div>

	<div class="form-group">
            <?php echo $form->labelEx($model, 'name'); ?>
            <?php echo $form->textField($model, 'name', ['size' => 60, 'maxlength' => 255, 'class' => 'form-control']); ?>
            <?php echo $form->error($model, 'name'); ?>
	</div>

	<div class="form-group">
            <?php echo $form->labelEx($model, 'minPrice'); ?>
            <?php echo $form->textField($model, 'minPrice', ['class' => 'form-control right-align']); ?>
            <?php echo $form->error($model, 'minPrice'); ?>
	</div>

	<div class="form-group">
            <?php echo CHtml::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->