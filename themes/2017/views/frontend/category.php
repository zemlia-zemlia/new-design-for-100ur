<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._header');
?>

    <div class="top-form-replace"></div>

    <div class="container">
        <div class="row">
            <div id="left-panel"></div>

            <div class="col-sm-9" id="center-panel">
                <?php echo $content; ?>
            </div>

            <div class="col-sm-3" id="right-panel">
                <?php if (Yii::app()->user->role == User::ROLE_JURIST): ?>


                    <div class="vert-margin20">
                        <?php
                        // выводим виджет с поиском вопросов
                        $this->widget('application.widgets.SearchQuestions.SearchQuestionsWidget', array());
                        ?>
                    </div>

                    <div class="vert-margin20">
                        <?php
                        // выводим виджет со статистикой ответов
                        $this->widget('application.widgets.MyAnswers.MyAnswers', array());
                        ?>
                    </div>
                <?php endif; ?>



                <?php if (Yii::app()->user->role != User::ROLE_JURIST): ?>

                    <div data-spy="" data-offset-top="200" class="inside hidden-xs">
                        <!--
                        <div class="consult-phone-widget vert-margin20">
                            <h4><span class="glyphicon glyphicon-earphone"></span> Горячая линия юридических консультаций</h4>
                            <h3>для Москвы и МО:</h3>
                            <p class="vert-margin20"><strong>8 499 255-69-85</strong></p>
                            <h3>для Санкт Петербурга и ЛО:</h3>
                            <p class="vert-margin20"><strong>8 812 466-87-81</strong></p>
                            <h3>для других регионов:</h3>
                            <?php echo CHtml::link('Запрос на обратный звонок ', Yii::app()->createUrl('question/call'), array('class' => 'button button-green-border')); ?>
                        </div>
                        -->

                        <div class="question-docs-block vert-margin20">
                            <h3>Вы также можете задать свой вопрос и получить ответ прямо на сайте</h3>
                            <?php echo (!stristr($_SERVER['REQUEST_URI'], '/question/create/')) ? CHtml::link('Задать вопрос online', Yii::app()->createUrl('question/create') . '?utm_source=100yuristov&utm_medium=question-docs-block&utm_campaign=' . Yii::app()->controller->id, array('class' => 'button button-green-border')) : ''; ?>
                            <br/>
                            <br/>
                            <h3>Заказать юридический документ у профессиональных юристов</h3>
                            <?php echo (!stristr($_SERVER['REQUEST_URI'], '/question/docs/')) ? CHtml::link("Заказать документ", Yii::app()->createUrl('question/docs'), array('class' => 'button button-green-border btn-block')) : '<span class="active">Заказать документы</span>'; ?>

                        </div>
                    </div>
                <?php endif; ?>

                <div class="inside article-preview">
                    <?php
                    $this->widget('application.widgets.RecentCategories.RecentCategories', [
                        'number' => 5,
                    ]);
                    ?>
                </div>


            </div>
        </div>
    </div>

<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._footer');
?>