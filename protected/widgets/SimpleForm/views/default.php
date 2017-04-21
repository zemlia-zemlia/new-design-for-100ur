<div id="hero">
    <div  class="container">
    <div class="row">
        <div class="col-md-4">
            
        </div>
        <div class="col-md-8">
            <div class="form-container">
                <h2 class="center-align">Задайте вопрос юристу прямо сейчас:</h2>
                                
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
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ваше имя *</label>
                                    <?php echo $form->textField($model,'authorName', array('class'=>'form-control', 'placeholder'=>'Владимир')); ?>
                                    <?php echo $form->error($model,'authorName'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="form-submit-wrapper">
                                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Задать вопрос юристу' : 'Сохранить', array('class'=>'button button-blue-gradient', 'onclick'=>'yaCounter26550786.reachGoal("simple_form_submit"); return true;')); ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="col-md-5">
                        <div class="form-info-item" style="background-image: url(/pics/2017/icon_quick.png);">
                            <h3 class="left-align">Это быстро</h3>
                            <p>
                            Вы получите ответ через 15 минут</p>
                        </div>
                        <div class="form-info-item" style="background-image: url(/pics/2017/icon_safe.png);">
                            <h3 class="left-align">Безопасно</h3>
                            <p>
                            Только аккредитованные юристы</p>
                        </div>
                        <div class="form-info-item" style="background-image: url(/pics/2017/icon_no_spam.png);">
                            <h3 class="left-align">Без спама</h3>
                            <p>
                            Мы никогда не рассылаем рекламу</p>
                        </div>
                    </div>
                </div> 
                
                <?php $this->endWidget(); ?>
                
            </div><!-- .form-container-->
        </div>
    </div>

    </div>
</div>