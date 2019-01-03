<div id="footer">
    <div class='container'>
        <div class='row'>

            <div class="col-sm-3">
                <h3 class="left-align">100 Юристов</h3>  
                <div class="vert-margin10">
                    <?php echo ($_SERVER['REQUEST_URI'] != '/site/about/') ? CHtml::link('О проекте', Yii::app()->createUrl('/site/about/')) : '<span class="active">О проекте</span>'; ?><br />
                    <?php echo ($_SERVER['REQUEST_URI'] != '/site/offer/') ? CHtml::link('Пользовательское соглашение', Yii::app()->createUrl('/site/offer/')) : '<span class="active">Пользовательское соглашение</span>'; ?><br />
					<?php echo ($_SERVER['REQUEST_URI'] != '/site/contacts/') ? CHtml::link('Наши филиалы', Yii::app()->createUrl('/site/contacts')) : '<span class="active">Наши филиалы</span>'; ?><br />

                </div>

                <div id="social-icons-container">
                    <noindex>
                        <a href="https://www.instagram.com/100yuristov/" target="_blank" rel="nofollow"><img src="/pics/2017/ig_icon.png" alt="Instagram" /></a>
                        <a href="https://vk.com/sto_yuristov" target="_blank" rel="nofollow"><img src="/pics/2017/vk_icon.png" alt="VK" /></a>
                        <a href="https://ok.ru/group/53087450366125" target="_blank" rel="nofollow"><img src="/pics/2017/ok_icon.png" alt="Одноклассники" /></a>
                        <a href="https://www.youtube.com/channel/UCgleswVaxaLKwL-MeGDmtfQ" target="_blank" rel="nofollow"><img src="/pics/2017/yt_icon.png" alt="Youtube" /></a>
                        <a href="https://www.facebook.com/100-%D0%AE%D1%80%D0%B8%D1%81%D1%82%D0%BE%D0%B2-1384104981880799/" target="_blank" rel="nofollow"><img src="/pics/2017/fb_icon.png" alt="Facebook" /></a>
                        <a href="https://twitter.com/stoyuristov" target="_blank" rel="nofollow"><img src="/pics/2017/tw_icon.png" alt="Twitter" /></a>
                    </noindex>
                </div>
                <small>&copy;2014 «100 Юристов» </small>
            </div>
            <div class="col-sm-2">
                <h3 class="left-align">Клиентам</h3>
                <?php echo ($_SERVER['REQUEST_URI'] != '/region/') ? CHtml::link('Каталог юристов', Yii::app()->createUrl('/region/country', ['countryAlias' => 'russia'])) : '<span class="active">География</span>'; ?><br />
                <?php echo ($_SERVER['REQUEST_URI'] != '/q/') ? CHtml::link('Архив вопросов', Yii::app()->createUrl('/question/index')) : '<span class="active">Архив вопросов</span>'; ?> <br />			
                <?php echo ($_SERVER['REQUEST_URI'] != '/site/goryachaya_liniya/') ? CHtml::link('Горячая линия', Yii::app()->createUrl('/site/goryachaya_liniya/')) : '<span class="active">Горячая линия</span>'; ?><br />
                <?php echo ($_SERVER['REQUEST_URI'] != '/blog/') ? CHtml::link('Блог проекта', Yii::app()->createUrl('/blog')) : '<span class="active">Блог проекта</span>'; ?><br />


            </div>
            <div class="col-sm-2">
                <h3 class="left-align">Юристам</h3>
                <p>
                  <?php echo ($_SERVER['REQUEST_URI'] != '/site/crm/') ? CHtml::link('CRM для юристов', Yii::app()->createUrl('/site/crm/')) : '<span class="active">CRM для юристов</span>'; ?> <br />
                  <?php echo ($_SERVER['REQUEST_URI'] != '/site/lead/') ? CHtml::link('Юридические заявки', Yii::app()->createUrl('/site/lead/')) : '<span class="active">Юридические заявки</span>'; ?><br />
                </p>

            </div>
            <div class="col-sm-2">
                <h3 class="left-align">Партнерам</h3>
                <p>
                    <?php echo ($_SERVER['REQUEST_URI'] != '/site/referal/') ? CHtml::link('Реферальная программа', Yii::app()->createUrl('/site/referal/')) : '<span class="active">Реферальная программа</span>'; ?><br />
                    <?php echo ($_SERVER['REQUEST_URI'] != '/site/partners/') ? CHtml::link('Вебмастерам', Yii::app()->createUrl('/site/partners/')) : '<span class="active">Вебмастерам</span>'; ?><br />
                </p>
            </div>
            <div class="col-sm-3">
                <div itemscope itemtype="http://schema.org/Organization" class="vert-margin20 small"> 
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                            <span itemprop="addressLocality">Москва</span> <span itemprop="streetAddress">Шлюзовая набережная д.6 стр.4</span>
                    </div>
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                            <span itemprop="addressLocality">Санкт-Петербург</span> <span itemprop="streetAddress">Ул. Достоевского д.25</span>
                    </div>
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                            <span itemprop="addressLocality">Нижний Новгород</span> <span itemprop="streetAddress">Ул. Новая, д. 28</span>
                    </div>
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                            <span itemprop="addressLocality">Екатеринбург</span> <span itemprop="streetAddress">Ул. 8 Марта, д. 142</span>
                    </div>
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                            <span itemprop="addressLocality">Ростов-на-Дону</span> <span itemprop="streetAddress">Ул. Красноармейская, д. 142/50</span>
                    </div>
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                            <span itemprop="addressLocality">Краснодар</span> <span itemprop="streetAddress">Ул. Московская, 148</span>
                    </div> 
                        <span itemprop="name">100 Юристов</span>: 
                        <span itemprop="telephone">88005006185</span>
                </div>
                    
            </div>

        </div>     
    </div>
</div>

<?php if (Yii::app()->user->role != User::ROLE_ROOT): ?>    
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-56588534-1', 'auto');
        ga('send', 'pageview');

    </script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter26550786 = new Ya.Metrika2({
                    id:26550786,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/tag.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks2");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/26550786" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

    <!-- Rating@Mail.ru counter -->
    <script type="text/javascript">
        var _tmr = _tmr || [];
        _tmr.push({id: "2577054", type: "pageView", start: (new Date()).getTime()});
        (function (d, w) {
            var ts = d.createElement("script");
            ts.type = "text/javascript";
            ts.async = true;
            ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
            var f = function () {
                var s = d.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(ts, s);
            };
            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else {
                f();
            }
        })(document, window);
    </script><noscript><div style="position:absolute;left:-10000px;">
        <img src="//top-fwz1.mail.ru/counter?id=2577054;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
    </div></noscript>
    <!-- //Rating@Mail.ru counter -->
    <!-- new hosting! -->


<?php endif; ?>
    
<?php if (Yii::app()->user->isGuest): ?>
    <script type="text/javascript">
        $(function () {
            document.oncopy = addLink;
        })

    </script>
<?php endif; ?>

<div id="go-top">
    <a href="#" class="btn btn-info">Наверх <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span></a>
</div>

<!-- 

<script>
    var obj = [
        {step1: {item: "Здравствуйте. Я администратор портала 100 Юристов. Я помогу Вам сформулировать вопрос юристам."}},
        {step2: {item: "Какой у Вас вопрос?"}},
        {step3: {item: 'Если вам сложно сформулировать вопрос юрист может сам вам перезвонить, звонок для вас бесплатный. <br/>' +
                    ' <a class="" href="/question/call/">Заказать звонок юриста</a>'}
        }];

    function slideRobotChat() {
        $("#robot_chat__contentMess").html(""),
            $("#robot_chat__contentMess").empty(),
            !0 === $("#robot_chat").hasClass("show") ? ($("#robot_chat").removeClass("show"),
                $("#robot_chat__wrap").hide(), $("#robot_chat__header1 .addq__small-info-bl1").show(),
                $("#robot_chat__header1 .robot_chat__header__close").hide(),
                stat_ya("CLF2"), addMess(!0)) : ($("#robot_chat").addClass("show"),
                $("#robot_chat__wrap").show(),
                $("#question_komm_bottom").focus(),
                $("#robot_chat__header1 .addq__small-info-bl1").hide(),
                $("#robot_chat__header1 .robot_chat__header__close").show(),
                $("#robot_chat_printed").show(), setTimeout(function () {
                addMess()
            }, 1500))
    }

    function addMess(s) {
        var a = 0;
        $("#robot_chat__contentMess").empty(), f = function () {
            if (s) return stopAllTimeouts(), !1;
            var t = obj[a]["step" + parseInt(a + 1)].time = robotGetTime(), o = obj[a]["step" + parseInt(a + 1)].item;
            $("#robot_chat_printed").hide();
            var e = '<div class="robot_chat_item"><div class="robot_chat_item__content">' + o + '</div><div class="robot_chat_item__date">' + t + "</div></div>";
            $("#robot_chat__contentMess").append(e), (a += 1) < 3 && (setTimeout(f, "3000"), $("#robot_chat_printed").fadeIn())
        }, f()
    }

    function robotGetTime() {
        var t = new Date, o = t.getHours(), e = t.getMinutes();
        return o < 10 && (o = "0" + o.toLocaleString()), e < 10 && (e = "0" + e.toLocaleString()), o + ":" + e
    }
</script>

<script>
    $(document).ready(function () {
        if (site_vars.current_user.id == 0 && site_vars.current_user.id !== undefined) {
            $(window).scroll(function form_bottom_show() {
                if (window.pageYOffset >= 900) {
                    slideRobotChat();
                    $("#question_komm_bottom").focus();
                    $(window).unbind('scroll', form_bottom_show);
                }
            });
        }
    });
</script>

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
                <span class="addq__small-info-bl-small-txt small-txt-l">Ответ через 15 минут</span>
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

        <form class="addq__quest_form" id="addq_form" action="/question/create/?utm_source=100yuristov&utm_medium=robot&utm_campaign=site" method="post">
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

-->

</body>
</html>