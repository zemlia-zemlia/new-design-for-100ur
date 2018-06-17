<!doctype html>
<html lang="ru">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <?php
        Yii::app()->clientScript->registerCssFile("/bootstrap/css/bootstrap.min.css");
        Yii::app()->clientScript->registerCssFile("/css/2017/style.css");
        Yii::app()->clientScript->registerScriptFile("/js/respond.min.js");
        Yii::app()->clientScript->registerScriptFile("jquery.js");
        Yii::app()->clientScript->registerScriptFile("/bootstrap/js/bootstrap.min.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile("/js/jquery.maskedinput.min.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerCssFile('/css/2017/jquery-ui.css');
        Yii::app()->clientScript->registerScriptFile('/js/jquery-ui.min.js');
        Yii::app()->clientScript->registerScriptFile("/js/jquery.placeholder.min.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile("/js/scripts.js", CClientScript::POS_END);
        ?>
    </head>  

    <body>
        <div id="header">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-3 center-align">
                        <div class="logo-wrapper">
                            <?php if ($_SERVER['REQUEST_URI'] != '/'): ?>
                                <a href="/">
                                    <img src="/pics/2017/logo_white.png" alt="100 Юристов и Адвокатов" title="Юридический портал"/>
                                </a>
                            <?php else: ?>
                                <img src="/pics/2017/logo_white.png" alt="100 Юристов и Адвокатов" title="Юридический портал" />
                            <?php endif; ?>      
                        </div>			
 
                    </div>

                    <?php if (Yii::app()->user->isGuest): ?>
                        <div class="col-md-3 col-sm-3 center-align"></div>
                        <div class="col-md-6 col-sm-6 center-align">
                        <?php else: ?>
                            <div class="col-md-6 col-sm-6 center-align"></div>
                            <div class="col-md-3 col-sm-3 center-align">  
                            <?php endif; ?>

                            <?php if (Yii::app()->user->isGuest): ?>

                                <?php
                                // выводим виджет с номером 8800
                                $this->widget('application.widgets.Hotline.HotlineWidget', array(
                                    'showAlways' => true,
                                ));
                                ?>


                            <?php else: ?>                    
                                <?php
                                // выводим виджет с формой логина
                                $this->widget('application.widgets.Login.LoginWidget', array(
                                ));
                                ?>
                            <?php endif; ?>
                        </div>  
                    </div>
                </div>
            </div> <!-- #header -->


                <nav class="navbar navbar-inverse">
                    <div id="top-menu-wrapper">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li><?php echo ($_SERVER['REQUEST_URI'] != '/webmaster/')?CHtml::link('Главная', '/webmaster/'):'<span class="active">Главная</span>';?></li>
                            <li><?php echo ($_SERVER['REQUEST_URI'] != '/webmaster/lead/')?CHtml::link('Лиды', Yii::app()->createUrl('/webmaster/lead/')):'<span class="active">Лиды</span>';?></li>
                            <li><?php echo ($_SERVER['REQUEST_URI'] != '/webmaster/lead/prices/')?CHtml::link('Цены', Yii::app()->createUrl('/webmaster/lead/prices/')):'<span class="active">Цены</span>';?></li>
                            <li><?php echo ($_SERVER['REQUEST_URI'] != '/webmaster/question/')?CHtml::link('Вопросы', Yii::app()->createUrl('/webmaster/question/')):'<span class="active">Вопросы</span>';?></li>
                            <li><?php echo ($_SERVER['REQUEST_URI'] != '/webmaster/source/')?CHtml::link('Источники', Yii::app()->createUrl('/webmaster/source/')):'<span class="active">Источники</span>';?></li>
                            <li><?php echo ($_SERVER['REQUEST_URI'] != '/webmaster/api/')?CHtml::link('API', Yii::app()->createUrl('/webmaster/api/')):'<span class="active">API</span>';?></li>
                            <li><?php echo ($_SERVER['REQUEST_URI'] != '/webmaster/faq/')?CHtml::link('FAQ', Yii::app()->createUrl('/webmaster/faq/')):'<span class="active">FAQ</span>';?></li>
                        </ul>
                        </div>
                    </div>
                    </nav>
            
            <div id="middle ">
                <div class="container-fluid container">


                    <div class="col-md-3 col-sm-4 inside">
                        <?php $sources = Leadsource::getSourcesByUser(Yii::app()->user->id);?>
                        
                            <div class="">
                                <h1>Мои источники</h1>
                            </div>
                        
                        <?php if(sizeof($sources) == 0):?>
                        <p>
                            Для начала заработка создайте хотя бы один источник лидов или трафика
                        </p>
						<?php echo CHtml::link('Создать источник', Yii::app()->createUrl('/webmaster/source/create'), array('class' => 'btn btn-block btn-primary'));?>
                        <?php endif;?>
                        <?php foreach($sources as $source):?>
                            <div class="flat-panel" >
                                <div class="inside">
                                    <h4>
                                        <?php echo CHtml::link($source->name, Yii::app()->createUrl('/webmaster/source/view', array('id'=>$source->id)));?>
                                    </h4>
                                    <p class="text-center">
                                        <small>Привлекаем <?php echo $source->getTypeName();?>
                                            <?php if($source->type == Leadsource::TYPE_LEAD):?>
                                                <?php echo CHtml::link('Добавить вручную', Yii::app()->createUrl('/webmaster/lead/create', array('sourceId' => $source->id)));?>
                                            <?php endif;?>
                                        </small></p>
                                    <?php if($source->description):?>
                                    <p class="text-center">                            
                                        <?php echo CHtml::encode($source->description);?>
                                    </p>
                                    <?php endif;?>

                                </div>
                            </div><br/>	
                        <?php endforeach;?>
						
                    </div>
					<p>
                    <div class="col-md-9 col-sm-8">
                        <?php echo $content; ?>
                    </div>
					</p>
                </div>
            </div>
			<br/>
            <div id="footer" class="container">
                <div >
                    <div class="text-center">
                        <p><strong>Возникли вопросы?</strong><br /> Задайте их техподдержке: admin@100yuristov.com, ответим оперативно.</p>
                    </div>
                </div>
            </div>

            <?php if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1'): ?>  

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
                <noscript><div><img src="//mc.yandex.ru/watch/26550786" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
                <!-- /Yandex.Metrika counter -->
            <?php endif; ?>

    </body>
</html>