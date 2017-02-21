<?php $form=$this->beginWidget('CActiveForm', array(
        'id'                    =>  'question-form',
        'enableAjaxValidation'  =>  false,
        'action'                =>  Yii::app()->createUrl('question/create'),
)); ?>

<div class="row">
    <div class="col-md-7">
        <div class="form-group">
                <?php echo $form->labelEx($newQuestionModel,'questionText'); ?>
                <?php echo $form->textArea($newQuestionModel,'questionText', array('class'=>'form-control', 'rows'=>6, 'placeholder'=>'Добрый день!...')); ?>
                <?php echo $form->error($newQuestionModel,'questionText'); ?>
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            <label>Ваше имя *</label>
            <?php echo $form->textField($newQuestionModel,'authorName', array('class'=>'form-control', 'placeholder'=>'Иванов Иван')); ?>
            <?php echo $form->error($newQuestionModel,'authorName'); ?>
        </div>
        <div class="form-group" id="form-submit-wrapper">
                <?php echo CHtml::submitButton($newQuestionModel->isNewRecord ? 'Задать вопрос юристу' : 'Сохранить', array('class'=>'button  button-blue-gradient btn-block', 'onclick'=>'yaCounter26550786.reachGoal("simple_form_submit"); return true;')); ?>
        </div>
                        </div>
</div> 
<?php $this->endWidget(); ?>