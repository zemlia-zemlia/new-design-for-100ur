<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'question-form',
    'enableAjaxValidation' => false,
]); ?>

<div class="row">
    <div class="col-md-9">
        <?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>
        <?php echo $form->hiddenField($model, 'id'); ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'title'); ?>
            <?php echo $form->textField($model, 'title', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'title'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'questionText'); ?>
            <?php echo $form->textArea($model, 'questionText', ['class' => 'form-control', 'rows' => 15]); ?>
            <?php echo $form->error($model, 'questionText'); ?>
        </div> 
        
        <?php if (true == $showMy):?>
            Отредактирован вами <?php echo DateHelper::niceDate($model->moderatedTime); ?>
        <?php endif; ?>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <?php echo CHtml::submitButton('Сохранить + следующий', ['class' => 'btn btn-primary btn-block']); ?>
        </div>
        <div class="form-group">
            <?php echo CHtml::ajaxLink('В спам', Yii::app()->createUrl('/admin/question/toSpam'), ['data' => 'id=' . $model->id, 'type' => 'POST', 'success' => 'onSpamSingleQuestion', 'beforeSend' => "function( xhr ) {return confirm('Вы уверены?');}"], ['class' => 'btn btn-warning btn-block']); ?>
        </div>
        
        <?php $lastQuestionId = $_COOKIE['lastModeratedQuestionId']; ?>
        <?php if ($lastQuestionId > 0):?>
        <div class="form-group">
            <?php echo CHtml::link('Назад', Yii::app()->createUrl('/admin/question/setTitle', ['id' => $_COOKIE['lastModeratedQuestionId']]), ['class' => 'btn btn-default btn-block']); ?>
        </div>
        <?php endif; ?>
        
        <div class="form-group alert alert-danger">
            <?php echo CHtml::checkBox('my', $showMy, ['class' => '']); ?> Исправленные мной
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>