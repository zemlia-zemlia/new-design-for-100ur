<div id="hero">
    <div  class="container">
    <div class="row">
        <div class="col-md-6">
        
        </div>
        <div class="col-md-6">
            
            <div class="right-align">
                    <?php if(Yii::app()->user->role != User::ROLE_JURIST):?>	
                        <div>
                            <?php
                                $questionsCountInt = Question::getCount()*2;
                                $questionsCount = str_pad((string)$questionsCountInt,6, '0',STR_PAD_LEFT);
                                $numbers = str_split($questionsCount);
                                $answersCount = str_pad((string)round($questionsCountInt*1.684),6, '0',STR_PAD_LEFT);;
                                $numbersAnswers = str_split($answersCount);
                            ?>
                                <div class="questions-counter-description">
                                    <div class="center-align">
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-6">
                                                <p>Задано вопросов</p>
                                                <p class="kpi-counter">
                                                    <img src="/pics/2017/icon_question.png" alt="100 Юристов и Адвокатов" title="Юридический портал" class="hidden-xs" />
                                                    <?php foreach($numbers as $num):?><span><?php echo $num;?></span><?php endforeach;?><br />
                                                </p>
                                            </div>
                                            <div class="col-sm-6 col-xs-6">
                                                <p>На них дано ответов</p>
                                                <p class="kpi-counter">
                                                    <img src="/pics/2017/icon_answer.png" alt="100 Юристов и Адвокатов" title="Юридический портал" class="hidden-xs" />
                                                    <?php foreach($numbersAnswers as $num):?><span><?php echo $num;?></span><?php endforeach;?><br />
                                                </p>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                        </div>
                    <?php endif;?>
                </div>
            
            <div class="form-container">
                <h2 class="center-align">Задайте вопрос юристу. Это бесплатно.</h2>
                <p class="center-align">
                    Получите ответ за 15 минут
                </p>
                                
                <?php $form=$this->beginWidget('CActiveForm', array(
                        'id'                    =>  'question-form',
                        'enableAjaxValidation'  =>  false,
                        'action'                =>  Yii::app()->createUrl('question/create'),
                )); ?>
                
                
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
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
                                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Получить ответ на вопрос' : 'Сохранить', array('class'=>'yellow-button btn-block', 'onclick'=>'yaCounter26550786.reachGoal("simple_form_submit"); return true;')); ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <?php $this->endWidget(); ?>
                
            </div><!-- .form-container-->
        </div>
    </div>

    </div>
</div>