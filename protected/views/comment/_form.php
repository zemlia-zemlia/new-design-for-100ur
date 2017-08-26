<?php
/* @var $this CommentController */
/* @var $model Comment */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false,
)); ?>


        <?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>
	
        <?php if(Yii::app()->user->isGuest):?>
        <div class="form-group row">
                <div class="col-sm-4">
                    <strong>Ваше имя:</strong>
                    <?php echo $form->textField($model,'authorName',array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'authorName'); ?>
                </div>
	</div>
        <?php endif;?>
    
	<div class="form-group">
		<?php echo $form->textArea($model,'text',array('rows'=>6,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>
    
        <?php if(!$hideRating):?>
            <div class="form-group">
                <strong>Поставьте оценку</strong>
                <?php $this->widget('CStarRating',array('name'=>'Comment[rating]', 'value'=>$model->rating, 'maxRating'=>5));?>
            </div>
        <?php endif;?>
    
        <?php echo $form->hiddenField($model, 'type', array('value'=>$type));?>
        <?php echo $form->hiddenField($model, 'objectId', array('value'=>$objectId));?>
        <?php echo $form->hiddenField($model, 'parentId', array('value'=>$parentId));?>

	
	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Ответить' : 'Сохранить', array('class'=>'yellow-button center-block')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->