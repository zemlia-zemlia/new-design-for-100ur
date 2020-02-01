<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'post-comment-form',
    'enableAjaxValidation' => false,
)); ?>

<?php echo $form->errorSummary($model); ?>
<div class="form-group">
    <?php echo $model->getAttributeLabel('text'); ?><br/>
    <?php echo $form->textArea($model, 'text', array('class' => 'form-control', 'rows' => '6')); ?>
    <?php echo $form->error($model, 'text'); ?>
</div>
<br/>
<?php echo CHtml::submitButton($model->isNewRecord ? 'Оставить комментарий' : 'Сохранить комментарий', array('class' => 'btn btn-primary')); ?>

<?php $this->endWidget(); ?>