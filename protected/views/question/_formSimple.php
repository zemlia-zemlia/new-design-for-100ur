<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $form CActiveForm */
?>

<div class="row">
    <div class="col-md-6">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'                    =>  'question-form',
	'enableAjaxValidation'  =>  false,
        'action'                =>  Yii::app()->createUrl('question/create'),
)); ?>

        <div class="form-group">
		<?php echo $form->labelEx($model,'questionText'); ?>
		<?php echo $form->textArea($model,'questionText', array('class'=>'form-control', 'rows'=>6)); ?>
		<?php echo $form->error($model,'questionText'); ?>
	</div>

        <div class="form-group">
                <label>Ваше имя *</label>
		<?php echo $form->textField($model,'authorName', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'authorName'); ?>
	</div>

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Задать вопрос юристу' : 'Сохранить', array('class'=>'btn btn-primary', 'onclick'=>'yaCounter26550786.reachGoal("simple_form_submit"); return true;')); ?>
            
        </div>
        <p>Вы получите ответ в рабочее время в течение 15 минут</p>

<?php $this->endWidget(); ?>
    </div>
    
    <div class="col-md-6">
        <div class="form-info-item" style="background-image:url(/pics/icon_rocket.svg);">
            <p><strong>Быстро</strong><br />
            Ответ через 15 минут</p>
        </div>
        <div class="form-info-item" style="background-image:url(/pics/icon_lock.svg);">
            <p><strong>Безопасно</strong><br />
            Только аккредитованные юристы</p>
        </div>
        <div class="form-info-item" style="background-image:url(/pics/icon_calculator.svg);">
            <p><strong>Экономия времени и денег</strong><br />
            Не надо ждать и искать - просто отправьте вопрос</p>
        </div>
        <div class="form-info-item" style="background-image:url(/pics/icon_email.svg);">
            <p><strong>Без спама</strong><br />
            Мы не рассылаем рекламу</p>
        </div>
    </div>
</div><!-- form -->