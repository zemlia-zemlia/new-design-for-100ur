<?php
/* @var $this QuestionController */
/* @var $model Question */
/* @var $form CActiveForm */
?>

<div id="form-wrapper">
    <div class="row">
        <div class="col-md-4">
            
        </div>
        <div class="col-md-8">
            <div class="form-container">
                <h2 class="center-align">Задайте вопрос юристу прямо сейчас</h2>
                <div class="center-align vert-margin30">Не надо ждать  и  искать - просто отправьте свой вопрос</div>
                
                <?php $form = $this->beginWidget('CActiveForm', [
                        'id' => 'question-form',
                        'enableAjaxValidation' => false,
                        'action' => Yii::app()->createUrl('question/create'),
                ]); ?>
                
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                                <?php echo $form->labelEx($model, 'questionText'); ?>
                                <?php echo $form->textArea($model, 'questionText', ['class' => 'form-control', 'rows' => 6, 'placeholder' => 'Добрый день!...']); ?>
                                <?php echo $form->error($model, 'questionText'); ?>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-info-item">
                            <p><span class="form-icon" style="background-position: 0 0;"></span><strong>Это быстро</strong><br />
                            Вы получите ответ через 15 минут</p>
                        </div>
                        <div class="form-info-item">
                            <p><span class="form-icon" style="background-position: -32px 0;"></span><strong>Безопасно</strong><br />
                            Только аккредитованные юристы</p>
                        </div>
                        <div class="form-info-item">
                            <p><span class="form-icon" style="background-position: -67px 0;"></span><strong>Без спама</strong><br />
                            Мы никогда не рассылаем рекламу</p>
                        </div>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label>Ваше имя *</label>
                            <?php echo $form->textField($model, 'authorName', ['class' => 'form-control', 'placeholder' => 'Иванов Иван']); ?>
                            <?php echo $form->error($model, 'authorName'); ?>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group" id="form-submit-wrapper">
                                <?php echo CHtml::submitButton($model->isNewRecord ? 'Задать вопрос юристу' : 'Сохранить', ['class' => 'button button-blue-gradient btn-block', 'onclick' => 'yaCounter26550786.reachGoal("simple_form_submit"); return true;']); ?>
                        </div>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
                <p class="center-align">Вы получите ответ в рабочее время в течение 15 минут</p>
                
            </div><!-- .form-container-->
        </div>
    </div>


</div> <!-- #form-wrapper -->