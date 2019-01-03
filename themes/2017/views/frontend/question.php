<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._header');
?>
<?
/**
 * Этот шаблон используется в разделах:
 * /cat/
 * /q/
 * /site/
 **/
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

                <?php if (Yii::app()->user->role == User::ROLE_JURIST): ?>
                	<div class="flat-panel inside">
                		<div class="row">
                			<div class="col-sm-3 center-align">
                				<img src="/pics/telegram-logo.jpg">
                			</div>
                			<div class="col-sm-9 small">
		                		<a href="https://t.me/joinchat/BHmZ1xNtdqMPkqxDX_dVvw" target="blank">Группа в телеграм для профессионального общения специалистов в отрасли права (ссылка)</a>
                			</div>
                		</div>
                	</div>
        	 	<?php endif; ?>

                <?php if (Yii::app()->user->role != User::ROLE_JURIST): ?>
                    <div data-spy="" data-offset-top="200">
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


                <?php if (Yii::app()->user->isGuest): ?>

        		<div class="inside">
					<!-- Yandex.RTB R-A-279595-2 -->
					<div id="yandex_rtb_R-A-279595-2"></div>
					<script type="text/javascript">
					    (function(w, d, n, s, t) {
					        w[n] = w[n] || [];
					        w[n].push(function() {
					            Ya.Context.AdvManager.render({
					                blockId: "R-A-279595-2",
					                renderTo: "yandex_rtb_R-A-279595-2",
					                async: true
					            });
					        });
					        t = d.getElementsByTagName("script")[0];
					        s = d.createElement("script");
					        s.type = "text/javascript";
					        s.src = "//an.yandex.ru/system/context.js";
					        s.async = true;
					        t.parentNode.insertBefore(s, t);
					    })(this, this.document, "yandexContextAsyncCallbacks");
					</script>
					</div>

                <?php endif; ?>

            </div>
        </div>
    </div>

<?php
CController::renderPartial('webroot.themes.2017.views.frontend.include._footer');
?>