<div id="form-wrapper">
    <div  class="container">
    <div class="row">
        <div class="col-md-4">
            
        </div>
        <div class="col-md-8">
            <div class="form-container">
                <h2 class="center-align">Задайте вопрос юристу прямо сейчас</h2>
                <div class="center-align">Не надо ждать  и  искать - просто отправьте свой вопрос</div>
                
                <?php $form=$this->beginWidget('CActiveForm', array(
                        'id'                    =>  'question-form',
                        'enableAjaxValidation'  =>  false,
                        'action'                =>  Yii::app()->createUrl('question/create'),
                )); ?>
                
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                                <?php echo $form->labelEx($model,'questionText'); ?>
                                <?php echo $form->textArea($model,'questionText', array('class'=>'form-control', 'rows'=>6, 'placeholder'=>'Меня хотят выписать из квартиры в которой не проживаю больше двух лет, как мне действовать чтобы сохранить прописку?')); ?>
                                <?php echo $form->error($model,'questionText'); ?>
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
                            <?php echo $form->textField($model,'authorName', array('class'=>'form-control', 'placeholder'=>'Владимир')); ?>
                            <?php echo $form->error($model,'authorName'); ?>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group" id="form-submit-wrapper">
                                <?php echo CHtml::submitButton($model->isNewRecord ? 'Задать вопрос юристу' : 'Сохранить', array('class'=>'btn btn-success btn-block', 'onclick'=>'yaCounter26550786.reachGoal("simple_form_submit"); return true;')); ?>
                        </div>
                    </div>
                </div>
                <?php $this->endWidget(); ?>
                <p class="center-align">Вы получите ответ в рабочее время в течение 15 минут</p>
                
            </div><!-- .form-container-->
        </div>
    </div>

    </div>
</div> <!-- #form-wrapper -->