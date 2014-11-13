<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $form CActiveForm */
?>

<div class="row">
    <div class="col-md-8 col-sm-6">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'question-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, "Для отправки вопроса укажите данные"); ?>
       
	<div class="form-group">
		<?php echo $form->labelEx($model,'questionText'); ?>
		<?php echo $form->textArea($model,'questionText', array('class'=>'form-control', 'rows'=>10)); ?>
		<?php echo $form->error($model,'questionText'); ?>
	</div>
        
        <div class="form-group">
		<?php echo $form->labelEx($model,'authorName'); ?>
		<?php echo $form->textField($model,'authorName', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'authorName'); ?>
	</div>
        
        <div class="form-group">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>
        
        <div class="form-group">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
        
        <div class="form-group">
		<?php echo $form->labelEx($model,'town'); ?>
		<?php echo $form->dropDownList($model,'townId', $townsArray, array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'townId'); ?>
	</div>

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Задать вопрос' : 'Сохранить', array('class'=>'btn btn-primary btn-lg')); ?>
	</div>

<?php $this->endWidget(); ?>

        <p class="text-muted">
            <small>
                *Респондент, заполнивший данную форму, дает свое согласие на обработку своих персональных данных, указанных в Анкете по юридической консультации, сервису 100yuristov.com, включая, сбор, систематизацию, накопление, хранение, уточнение, использование, обезличивание, распространение, блокирование, уничтожение (без уведомления об уничтожении) путем обработки автоматизированным или неавтоматизированным способом в целях осуществления своей деятельности на срок 10 лет. Отзыв согласия на обработку персональных данных должен быть осуществлен в письменной форме.
            </small>
        </p>
</div>
    
    <div class="col-md-4 col-sm-6">
        <div class="form-info-item" style="background-image:url(/pics/icon_rocket.png);">
            <p><strong>Быстро</strong><br />
            Ответ через 15 минут</p>
        </div>
        <div class="form-info-item" style="background-image:url(/pics/icon_safe.png);">
            <p><strong>Безопасно</strong><br />
            Только аккредитованные юристы</p>
        </div>
        <div class="form-info-item" style="background-image:url(/pics/icon_calculator.png);">
            <p><strong>Экономия времени и денег</strong><br />
            Не надо ждать и искать - просто отправьте вопрос</p>
        </div>
        <div class="form-info-item" style="background-image:url(/pics/icon_envelope.png);">
            <p><strong>Без спама</strong><br />
            Мы не рассылаем рекламу</p>
        </div>
    </div>
</div><!-- form -->