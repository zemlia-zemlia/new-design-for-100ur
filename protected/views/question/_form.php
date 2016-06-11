<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $form CActiveForm */
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'question-form',
	'enableAjaxValidation'=>false,
)); ?>

	

	<?php echo $form->errorSummary($model, "Для отправки вопроса укажите данные"); ?>
       
	<div class="form-group">
		<?php echo $form->labelEx($model,'questionText'); ?>
		<?php echo $form->textArea($model,'questionText', array('class'=>'form-control', 'rows'=>10, 'placeholder'=>'Добрый день!')); ?>
		<?php echo $form->error($model,'questionText'); ?>
	</div>
        
        <div class="form-group">
		<?php echo $form->labelEx($model,'authorName'); ?>
		<?php echo $form->textField($model,'authorName', array('class'=>'form-control', 'placeholder'=>'Иванов Иван')); ?>
		<?php echo $form->error($model,'authorName'); ?>
	</div>
        
        <div class="form-group">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone', array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>
        
        <div class="form-group">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email', array('class'=>'form-control', 'placeholder'=>'ivanov@mail.ru')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
        
        <div class="form-group">
		<?php echo $form->labelEx($model,'town'); ?>
                <?php echo CHtml::textField('town', '', array('id'=>'town-selector', 'class'=>'form-control')); ?>
                <?php
                    echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
                ?>
		<?php echo $form->error($model,'townId'); ?>
	</div>

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Отправить' : 'Сохранить', array('class'=>'btn btn-warning btn-block btn-lg')); ?>
	</div>

<?php $this->endWidget(); ?>

    <p class="note"><span class="required">*</span> - обязательные поля</p>

    <p class="text-muted">
        <small>
            *Респондент, заполнивший данную форму, дает свое согласие на обработку своих персональных данных, указанных в Анкете по юридической консультации, сервису 100yuristov.com, включая, сбор, систематизацию, накопление, хранение, уточнение, использование, обезличивание, распространение, блокирование, уничтожение (без уведомления об уничтожении) путем обработки автоматизированным или неавтоматизированным способом в целях осуществления своей деятельности на срок 10 лет. Отзыв согласия на обработку персональных данных должен быть осуществлен в письменной форме.
        </small>
    </p>


