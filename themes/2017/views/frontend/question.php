<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._header');
?>

<div class="container">
    <div class="top-form-replace">
        <hr/>
    </div>    
</div>

<div class="container">   
    <div class="row">
        <div id="left-panel"></div>

        <div class="col-sm-8" id="center-panel">
            <?php echo $content; ?>
        </div>    

        <div class="col-sm-4" id="right-panel">
            <?php if (Yii::app()->user->role == User::ROLE_JURIST): ?>


                <div class="vert-margin20">          
                    <?php
                    // выводим виджет с поиском вопросов
                    $this->widget('application.widgets.SearchQuestions.SearchQuestionsWidget', array(
                    ));
                    ?>
                </div> 

                <div class="vert-margin20">           
                    <?php
                    // выводим виджет со статистикой ответов
                    $this->widget('application.widgets.MyAnswers.MyAnswers', array(
                    ));
                    ?>
                </div>  			
            <?php endif; ?>


            <?php if (Yii::app()->user->isGuest): ?>
                <?php
                // выводим виджет Назойливый
                $this->widget('application.widgets.Annoying.AnnoyingWidget', array(
                    'showAlways' => true,
                ));
                ?>
            <?php endif; ?>

            <?php if (Yii::app()->user->role != User::ROLE_JURIST): ?>
                <div data-spy="" data-offset-top="200" class="hidden-xs">
                    <div class="vert-margin20">
                        <?php
                        // выводим виджет с формой
                        $this->widget('application.widgets.SimpleForm.SimpleForm', array(
                            'template' => 'sidebar',
                        ));
                        ?> 
                    </div>

                    <?php if (Yii::app()->user->isGuest): ?>
                        <div class="grey-panel inside">
                            <h4>Вы специалист в области права?</h4>
                            <p>
                                Вы можете отвечать на вопросы наших пользователей пройдя нехитрую процедуру 
                                регистрации и подтверждения вашей квалификации.
                            </p>
                            <p class="right-align">
                                <?php echo CHtml::link('Зарегистрироваться', Yii::app()->createUrl('/user/create', array('role' => User::ROLE_JURIST))); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if (!Yii::app()->user->isGuest): ?>
                <div class="vert-margin30 blue-block inside">
                    <h3 class="vert-margin20">Оплатим ваш мобильный!</h3>
                    <p>
                    <?php echo CHtml::link('Подробнее', Yii::app()->createUrl('site/referal'), ['class' => 'yellow-button btn-block text-center']); ?>
                    </p>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._footer');
?>