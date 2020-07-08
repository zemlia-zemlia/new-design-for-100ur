<?php
//Yii::app()->clientScript->registerScript('var robotWidgetQuestionUrl =' . Yii::app()->createUrl('/question/call/'). ';', CClientScript::POS_HEAD);

?>



<div id="robot_chat" class="noprint">
    <div class="robot_chat__header" id="robot_chat__header1" title="Свернуть" onclick="slideRobotChat(); return false;">
        <span class="robot_chat__header__close" style=" display: none"></span>
        <div class="robot_chat__header__img"><img src="/pics/15.png" alt="Консультация юристов и адвокатов"></div>
        <div class="addq__small-info-bl1">
            <span>
                спросить
            </span>
        </div>
        <div class="addq__small-info-bl">
            Спросить юриста быстрее
            <span class="addq__small-info-bl-small">
                <span class="addq__small-info-bl-small-txt small-txt-l">Более <strong>700</strong> юристов готовы помочь</span>
            </span>
        </div>
        <div class="clear"></div>
    </div>

    <div class="robot_chat__header" id="robot_chat__header2" style="display: none" title="Свернуть"
         onclick="slideRobotChat(); return false;">
        <span class="robot_chat__header__close"></span>
        <div class="robot_chat__header__img"><img src="/pics/15.png" alt="Консультация юристов и адвокатов"></div>
        <div class="robot_chat__header__text">Задайте бесплатный<br>вопрос юристам</div>
        <div class="clear"></div>
    </div>

    <div class="clear"></div>

    <div id="robot_chat__wrap" style="" class="clearFix">
        <div id="robot_chat__body" class="clearFix">
            <div id="robot_chat__content">
                <div id="robot_chat__contentMess"></div>
                <div id="robot_chat_printed">Администратор печатает сообщение <img src="/pics/pen.gif" alt=""></div>
            </div>
            <div class="clear"></div>
        </div>

        <form class="addq__quest_form" id="addq_form"
              action="<?php echo Yii::app()->createUrl('/question/create/'); ?>?utm_source=100yuristov&utm_medium=robot&utm_campaign=site"
              method="post">
            <div class="input_robot_chat clearFix">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input_robot_chat_txt">

                        <textarea required="" onclick="$('').css('margin-bottom', '0');"
                                  id="question_komm_bottom" class="addq__quest_form_text" name="komm" rows="4"
                                  placeholder="Текст вашего вопроса юристу"></textarea>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="robot_button">
                            <input type="submit" name="asc_question"
                                   id="form-button_addq" class="btn yellow-button" value="Спросить">
                        </div>
                    </div>
                </div>

            </div>
            <input type="hidden" name="hidden_type_form" value="88">
            <input type="hidden" name="hidden_type_form_referer" value="">
            <input type="hidden" name="name" value="">
        </form>

    </div>
</div>
