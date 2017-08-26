<?php
/* @var $this AnswerController */
/* @var $model Answer */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'answer-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>

	<div class="form-group">
            <?php echo $form->textArea($model,'answerText',array('rows'=>6, 'class'=>'form-control')); ?>
            <?php echo $form->error($model,'answerText'); ?>
	</div>
    
        <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
            <div class="form-group">
                <?php echo $form->labelEx($model,'videoLink'); ?>
                <?php echo $form->textField($model,'videoLink', array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'videoLink'); ?>
            </div>
        <?php endif;?>

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class'=>'yellow-button center-block')); ?>
            </div>
        </div>
    </div>
	

<?php $this->endWidget(); ?>

</div><!-- form -->