<div id="hero">
    <div  class="container">
    <div class="row">
        <div class="col-md-6 hidden-xs">
            <div id="law-block">
                <div class="law-name"><a href="/user/8/?from=index-form" rel="nofollow">Тарасова Марина</a></div>
                <small>юрист проекта</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-container">
                                     
                <div class="form-title">
                    <h2 class="center-align">Задать вопрос юристу онлайн бесплатно</h2>                            
                </div> 

                <?php $form=$this->beginWidget('CActiveForm', array(
                        'id'                    =>  'question-form',
                        'enableAjaxValidation'  =>  false,
                        'action'                =>  Yii::app()->createUrl('question/create') . '?utm_source=100yuristov&utm_medium=hero&utm_campaign='.Yii::app()->controller->id,
                )); ?>
                
                
                
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                                <?php echo $form->textArea($model,'questionText', array('class'=>'form-control', 'rows'=>6, 'placeholder'=>'Опишите свою ситуацию максимально подробно чтобы юрист смог сориентироваться и дать максимально развернутый ответ')); ?>
                                <?php echo $form->error($model,'questionText'); ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ваше имя *</label>
                                    <?php echo $form->textField($model,'authorName', array('class'=>'form-control', 'placeholder'=>'Представьтесь')); ?>
                                    <?php echo $form->error($model,'authorName'); ?>
                    </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" id="form-submit-wrapper">
                                        <?php echo CHtml::submitButton($model->isNewRecord ? 'Получить ответ' : 'Сохранить', array('class'=>'yellow-button', 'onclick'=>'yaCounter26550786.reachGoal("simple_form_submit"); return true;')); ?>
                                </div>
                            </div>
                        </div>
                        <p class="">
                            Получите первый ответ уже через <strong>15 минут</strong>
                        </p>
                        
                    </div>
                </div>
                
                <?php $this->endWidget(); ?>
                
            </div><!-- .form-container-->
        </div>
    </div>

        <?php
            $questionsCountInt = Question::getCount()*2;
            $questionsCount = str_pad((string)$questionsCountInt,6, '0',STR_PAD_LEFT);
            $numbers = str_split($questionsCount);
            $answersCount = round($questionsCountInt*1.684);
            $numbersAnswers = str_split($answersCount);
            
            $yuristsCountRow = Yii::app()->db->createCommand()
                    ->select('COUNT(*) counter')
                    ->from('{{user}}')
                    ->where('role=:role', array(':role' => User::ROLE_JURIST))
                    ->queryRow();
            $yuristsCount = round($yuristsCountRow['counter']*5.314);
        ?>
        
        <div class="counters-wrapper">
            <div class="row">
                <div class="col-sm-3 center-align counter-green">
                    <div class="counter-number">
                        <span class="glyphicon glyphicon-bullhorn"></span> <?php echo $questionsCountInt;?>
                    </div>
                    <div class="counter-description">
                        вопросов задано
                    </div>
                </div>
                <div class="col-sm-3 center-align counter-yellow">
                    <div class="counter-number">
                        <span class="glyphicon glyphicon-comment"></span> <?php echo $answersCount;?>
                    </div>
                    <div class="counter-description">
                        ответов получено
                    </div>
                </div>
                <div class="col-sm-3 center-align counter-green">
                    <div class="counter-number">
                        <span class="glyphicon glyphicon-education"></span> <?php echo $yuristsCount;?>
                    </div>
                    <div class="counter-description">
                        юристов на сайте
                    </div>
                </div>
                <div class="col-sm-3 center-align counter-yellow">
                    <div class="counter-number">
                        <span class="glyphicon glyphicon-thumbs-up"></span>
                    </div>
                    <div class="counter-description">
                        нас рекомендуют
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>