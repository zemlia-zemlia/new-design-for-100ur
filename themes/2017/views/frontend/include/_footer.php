<div id="footer">
    <div class='container'>
        <div class='row'>

            <div class="col-sm-3">
                <h3 class="left-align">100 Юристов</h3>  
                <div class="vert-margin20">
                    <?php echo ($_SERVER['REQUEST_URI'] != '/site/about/') ? CHtml::link('О проекте', Yii::app()->createUrl('/site/about/')) : '<span class="active">О проекте</span>'; ?><br />
                    <?php echo ($_SERVER['REQUEST_URI'] != '/site/offer/') ? CHtml::link('Пользовательское соглашение', Yii::app()->createUrl('/site/offer/')) : '<span class="active">Пользовательское соглашение</span>'; ?><br />
                    <?php echo ($_SERVER['REQUEST_URI'] != '/region/') ? CHtml::link('Каталог юристов', Yii::app()->createUrl('/region/country', ['countryAlias' => 'russia'])) : '<span class="active">География</span>'; ?><br />
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
            </div>
            <div class="col-sm-3">
                <h3 class="left-align">Клиентам</h3>
                <?php echo ($_SERVER['REQUEST_URI'] != '/q/') ? CHtml::link('Новые вопросы', Yii::app()->createUrl('/question/index')) : '<span class="active">Новые вопросы</span>'; ?> <br />			
                <?php echo ($_SERVER['REQUEST_URI'] != '/company/') ? CHtml::link('Каталог компаний России', Yii::app()->createUrl('/company/')) : '<span class="active">Каталог компаний</span>'; ?><br />
                <?php echo ($_SERVER['REQUEST_URI'] != '/site/goryachaya_liniya/') ? CHtml::link('Горячая линия', Yii::app()->createUrl('/site/goryachaya_liniya/')) : '<span class="active">Горячая линия</span>'; ?><br />
                <?php echo ($_SERVER['REQUEST_URI'] != '/site/contacts/') ? CHtml::link('Наши филиалы', Yii::app()->createUrl('/site/contacts')) : '<span class="active">Наши филиалы</span>'; ?><br />
                <?php echo ($_SERVER['REQUEST_URI'] != '/blog/') ? CHtml::link('Советы юристов', Yii::app()->createUrl('/blog')) : '<span class="active">Советы юристов</span>'; ?>
            </div>
            <div class="col-sm-3">
                <h3 class="left-align">Юристам</h3>
                <p>
                    <?php echo ($_SERVER['REQUEST_URI'] != '/site/crm/') ? CHtml::link('CRM Для юридических фирм', Yii::app()->createUrl('/site/crm/')) : '<span class="active">CRM Для юридических фирм</span>'; ?><br />
                    <?php echo ($_SERVER['REQUEST_URI'] != '/site/lead/') ? CHtml::link('Юридические заявки', Yii::app()->createUrl('/site/lead/')) : '<span class="active">Юридические заявки</span>'; ?><br />
                    <?php echo ($_SERVER['REQUEST_URI'] != '/site/klienti_dlya_yuristov/') ? CHtml::link('Клиенты для юристов', Yii::app()->createUrl('/site/klienti_dlya_yuristov/')) : '<span class="active">Клиенты для юристов</span>'; ?><br />
                </p>
                <h3 class="left-align">Партнерам</h3>
                <p>
                    <?php echo ($_SERVER['REQUEST_URI'] != '/site/referal/') ? CHtml::link('Реферальная программа', Yii::app()->createUrl('/site/referal/')) : '<span class="active">Реферальная программа</span>'; ?><br />
                    <?php echo ($_SERVER['REQUEST_URI'] != '/site/partners/') ? CHtml::link('Вебмастерам', Yii::app()->createUrl('/site/partners/')) : '<span class="active">Вебмастерам</span>'; ?><br />
                </p>
            </div>

            <div class="col-sm-3">

                <div itemscope itemtype="http://schema.org/Organization" class="vert-margin20 small"> 
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                        <p>
                            <span itemprop="addressLocality">Москва</span> <span itemprop="streetAddress">Шлюзовая набережная д.6 стр.4</span>
                        </p>
                    </div>
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                        <p>
                            <span itemprop="addressLocality">Санкт-Петербург</span> <span itemprop="streetAddress">Ул. Достоевского д.25</span>
                        </p>
                    </div>
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                        <p>
                            <span itemprop="addressLocality">Нижний Новгород</span> <span itemprop="streetAddress">Ул. Новая, д. 28</span>
                        </p>
                    </div>
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                        <p>
                            <span itemprop="addressLocality">Екатеринбург</span> <span itemprop="streetAddress">Ул. 8 Марта, д. 142</span>
                        </p>
                    </div>
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                        <p>
                            <span itemprop="addressLocality">Ростов-на-Дону</span> <span itemprop="streetAddress">Ул. Красноармейская, д. 142/50</span>
                        </p>
                    </div>
                    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                        <p>
                            <span itemprop="addressLocality">Краснодар</span> <span itemprop="streetAddress">Ул. Московская, 148</span>
                        </p>
                    </div> 
                    <p>
                        <span itemprop="name">100 Юристов</span>: 

                        <span itemprop="telephone">8-800-500-61-85</span>
                    </p>
                </div>


            </div>
        </div>     
        <div class='row'>
            <div class='col-md-12 col-sm-12'>
                <p style="text-align: justify;"> 
                    <small>
                        <noindex>
                            &copy; Правовой портал «100 Юристов» 2014. Сайт предназначен для лиц старше 18 лет.	Все права, на любые материалы, размещенные на сайте, защищены в соответствии с российским и международным законодательством об авторском праве и смежных правах. При любом использовании текстовых, аудио-, видео- и фотоматериалов ссылка на www.100yuristov.com обязательна. Редакция сайта не несет ответственности за достоверность информации, опубликованной на сайте.  Email для связи с администрацией портала admin@100yuristov.com
                        </noindex>
                    </small>
                </p>
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
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function () {
                try {
                    w.yaCounter26550786 = new Ya.Metrika({id: 26550786,
                        webvisor: true,
                        clickmap: true,
                        trackLinks: true,
                        accurateTrackBounce: true});
                } catch (e) {
                }
            });

            var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () {
                        n.parentNode.insertBefore(s, n);
                    };
            s.type = "text/javascript";
            s.async = true;
            s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else {
                f();
            }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="//mc.yandex.ru/watch/26550786" style="position:absolute; left:-9999px;" alt="100 Юристов и Адвокатов" title="Юридический портал" /></div></noscript>
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
    
<?php if(Yii::app()->user->isGuest == false && in_array(Yii::app()->user->role, [User::ROLE_JURIST])):?>
    <script type="text/javascript" src="//api.venyoo.ru/wnew.js?wc=venyoo/default/science&widget_id=5214416909631488"></script>
<?php endif; ?>
    
<?php if (Yii::app()->user->isGuest): ?>
    <script type="text/javascript">
        $(function () {
            document.oncopy = addLink;
        })

    </script>
<?php endif; ?>

</body>
</html>