<!doctype html>
<html lang="ru">
<head>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600&subset=latin,cyrillic' rel='stylesheet' type='text/css'>

<?php 
    Yii::app()->clientScript->registerCssFile("/bootstrap/css/bootstrap.min.css");
    Yii::app()->clientScript->registerCssFile("/css/2017/style.css");
    Yii::app()->clientScript->registerScriptFile("/js/respond.min.js");
    Yii::app()->clientScript->registerScriptFile("jquery.js");
    Yii::app()->clientScript->registerScriptFile("/bootstrap/js/bootstrap.min.js",CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("/js/jquery.maskedinput.min.js",CClientScript::POS_END);
    Yii::app()->clientScript->registerCssFile('/css/2017/jquery-ui.css');
    Yii::app()->clientScript->registerScriptFile('/js/jquery-ui.min.js');
    
    Yii::app()->clientScript->registerScriptFile("/js/scripts.js");
    Yii::app()->clientScript->registerScriptFile("/js/jquery.placeholder.min.js",CClientScript::POS_END);

?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-56588534-1', 'auto');
  ga('send', 'pageview');

</script>

<style>
    
        
</style>
</head>  

<body>
    
        <div id="header">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3 center-align">
                    <div class="logo-wrapper">
                        <?php if($_SERVER['REQUEST_URI'] != '/'):?>
                        <a href="/">
                            <img src="/pics/2017/logo_white.png" alt="100 Юристов и Адвокатов" title="100 Юристов и Адвокатов"/>
                        </a>
                        <?php else:?>
                            <img src="/pics/2017/logo_white.png" alt="100 Юристов и Адвокатов" title="100 Юристов и Адвокатов" />
                        <?php endif;?>      
                    </div>			
                     <div class="logo-description">
                         <h5>юридические консультации онлайн</h5>   
                    </div>  
                </div>
		
                <?php if(Yii::app()->user->isGuest):?>
                    <div class="col-md-3 col-sm-3 center-align"></div>
                    <div class="col-md-6 col-sm-6 center-align">
                <?php else:?>
                    <div class="col-md-6 col-sm-6 center-align"></div>
                    <div class="col-md-3 col-sm-3 center-align">  
                <?php endif;?>
                
                    <?php if(Yii::app()->user->isGuest):?>
                            <?php
                                // выводим виджет с номером 8800
                                $this->widget('application.widgets.Hotline.HotlineWidget', array(
                                    'showAlways' => true,
                                ));
                            ?>

                    <?php else:?>                    
                        <?php
                            // выводим виджет с формой логина
                            $this->widget('application.widgets.Login.LoginWidget', array(
                            ));
                        ?>
                    <?php endif;?>
                </div>  
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
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/')?CHtml::link('Главная', '/'):'<span class="active">Главная</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/konsultaciya_yurista_advokata/')?CHtml::link('Консультация', Yii::app()->createUrl('/site/konsultaciya_yurista_advokata')):'<span class="active">Консультация</span>';?></li>
			<li><?php echo ($_SERVER['REQUEST_URI'] != '/yurist/')?CHtml::link('Юристы', Yii::app()->createUrl('/yurist/')):'<span class="active">Юристы</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/region/')?CHtml::link('Регионы', Yii::app()->createUrl('/region/')):'<span class="active">Регионы</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/contacts/')?CHtml::link('Филиалы', Yii::app()->createUrl('/site/contacts')):'<span class="active">Филиалы</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/blog/')?CHtml::link('Блог', Yii::app()->createUrl('/blog')):'<span class="active">Блог</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/q/')?CHtml::link('Вопросы', Yii::app()->createUrl('/question/index')):'<span class="active">Вопросы</span>';?></li>
            <li><?php echo (!stristr($_SERVER['REQUEST_URI'], '/question/create/'))?CHtml::link('Задать вопрос', Yii::app()->createUrl('question/create',array('from'=>'top-menu'))):'<span class="active">Задать вопрос</span>';?></li>
			<li><?php echo ($_SERVER['REQUEST_URI'] != '/site/goryachaya_liniya/')?CHtml::link('8-800', Yii::app()->createUrl('/site/goryachaya_liniya/')):'<span class="active">8-800</span>';?></li>
            <?php if(Yii::app()->user->checkAccess(User::ROLE_JURIST)):?>
            <li>    
                <?php echo CHtml::ajaxLink("Кеш", Yii::app()->createUrl('site/clearCache'), array(
                            'success'=>'function(data, textStatus, jqXHR)
                                {
                                    if(data==1) message = "Кэш очищен";
                                    else message = "Не удалось очистить кэш";
                                    alert(message);
                                }'    
                            ), array('title'    =>  'Очистить кеш страницы'));?>
            </li>
            <?php endif;?>
        </ul>
        </div>
    </div>
    </nav>
    
    <div class="container container600">
        <?php echo $content;?>
    </div>
    
    <!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter26550786 = new Ya.Metrika({id:26550786,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/26550786" style="position:absolute; left:-9999px;" alt="Яндекс" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<!-- Rating@Mail.ru counter -->
<script type="text/javascript">
var _tmr = _tmr || [];
_tmr.push({id: "2577054", type: "pageView", start: (new Date()).getTime()});
(function (d, w) {
   var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true;
   ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
   var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
   if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
})(document, window);
</script><noscript><div style="position:absolute;left:-10000px;">
<img src="//top-fwz1.mail.ru/counter?id=2577054;js=na" style="border:0;" height="1" width="1" alt="Рейтинг@Mail.ru" />
</div></noscript>
<!-- //Rating@Mail.ru counter -->

</body>
</html>