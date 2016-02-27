<!doctype html>
<html lang="ru">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php 
    Yii::app()->clientScript->registerCssFile("/bootstrap/css/bootstrap.min.css");
    Yii::app()->clientScript->registerCssFile("/css/2015/style.css");
    Yii::app()->clientScript->registerScriptFile("jquery.js",CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("/bootstrap/js/bootstrap.min.js",CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("/js/scripts.js");
    Yii::app()->clientScript->registerScriptFile("/js/jquery.placeholder.min.js",CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("/js/respond.min.js");
?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-56588534-1', 'auto');
  ga('send', 'pageview');

</script>
<meta name="c3b555200e34c47785fca1f69573ef95" content="">
</head>  

<body>
 <!-- 20a5d78ef98720e0 -->   
    <div id="top-menu-wrapper">
        <ul>
            <li><?php echo CHtml::link('Главная', '/');?></li>
            <li><?php echo CHtml::link('Партнеры', Yii::app()->createUrl('/site/partners'));?></li>
            <li><?php echo CHtml::link('Контакты', Yii::app()->createUrl('/site/contacts'));?></li>
            <li><?php echo CHtml::link('Блог', Yii::app()->createUrl('/blog'));?></li>
            <li><?php echo CHtml::link('Все вопросы', Yii::app()->createUrl('question'));?></li>
            <li><?php echo CHtml::link('Задать вопрос', Yii::app()->createUrl('question/create',array('from'=>'top-menu')));?></li>
			<li><a href="http://www.codecs.100yuristov.com/" target="blank">Кодексы РФ</a></li>
        </ul>
    </div>
    
    <div id="header">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4">
						<br/>
                    <a href="/"><img src="/pics/2015/logo.png" alt="100 юристов" /></a>
                    <div class="logo-description">
                        <b>Профессиональные юридические консультации</b>
						<br/>
                    </div>
                </div>
                <div class="col-md-5 col-sm-5">
					<br/> 
					<div class="header-phone"><b>Консультация юриста по телефону</b><br/> 
                    <b>(499) 322-45-41</b> - Москва и МО <br/> 
					<b>(812) 309-68-26</b> - Санкт Петербург и ЛО</div>
				</div>
                <div class="col-md-3 col-sm-3">
					<br/>
                    <div class="questions-counter">
                    <?php
                        $questionsCount = Question::getCountByStatus(Question::STATUS_PUBLISHED);
                        echo $questionsCount; 
                    ?>
                    </div>
					<br/>
                    <div class="questions-counter-description"><?php echo CustomFuncs::numForms($questionsCount, 'вопрос', "вопроса", "вопросов") ?> на сайте</div>
					
                   <!-- <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki" data-yashareTheme="counter"></div> -->
					<?php echo CHtml::link('<b>ЗАДАТЬ СВОЙ ВОПРОС</b>', Yii::app()->createUrl('question/create'), array('class'=>'btn btn-block btn-danger')); ?>

                </div>
            </div>
        </div>
    </div>
    
    
    <?php if(!(Yii::app()->controller->id=='question' && Yii::app()->controller->action->id=='create')):?>
        <?php
        // выводим виджет с формой
            $this->widget('application.widgets.SimpleForm.SimpleForm', array());
        ?>  
    <?php endif;?>
  
    
    <div id="middle">
        <div class="container">

 
            <div class="col-md-2 col-sm-2">
                <div id="left-bar" class="panel" >
                    <h4>Категории</h4>
					<div style="font-size; 11px">
					<small>
                    <?php
                    // выводим виджет с деревом категорий
                        $this->widget('application.widgets.CategoriesTree.CategoriesTree', array());
                    ?>
					</small>
					</div>
                </div>

                
                <?php echo CHtml::link('Задать вопрос', Yii::app()->createUrl('question/create'), array('class'=>'btn btn-block btn-danger')); ?>
            </div>
			<br/>
            <div class="col-md-8 col-sm-8">
                <?php echo $content;?>
                
                <?php if(!(Yii::app()->controller->id=='question' && Yii::app()->controller->action->id=='create')):?>
                    <?php
                    // выводим виджет с последними 4 вопросами
                        $this->widget('application.widgets.RecentPosts.RecentPosts', array());
                    ?>  
                <?php endif;?>
            </div>
            <div class="col-md-2 col-sm-2">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 col-xs-4">
                                <img class="img-responsive center-block" alt="Правительство Москвы" title="Правительство Москвы" src="/pics/pravitelstvo.png">
                            </div>
                            <div class="col-md-12 col-xs-4">
                                <img class="img-responsive center-block" alt="Министерство Юстиции" title="Министерство Юстиции" src="/pics/minyust.png"> 
                            </div>
                            <div class="col-md-12 col-xs-4">
                                <img class="img-responsive center-block" alt="Дума Российской Федерации" title="Дума Российской Федерации" src="/pics/duma.png"> 
                            </div>
                            <div class="col-md-12 col-xs-4">
                                <img class="img-responsive center-block" alt="Федеральная служба по контролю за наркотиками" title="Федеральная служба по контролю за наркотиками" src="/pics/fskn.png"> 
                            </div>
                            <div class="col-md-12 col-xs-4">
                                <img class="img-responsive center-block" alt="" title="" src="/pics/tpp.png"> 
                            </div>
                            <div class="col-md-12 col-xs-4">
                                <img class="img-responsive center-block" alt="" title="" src="/pics/cs.png"> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</div>
 
<? /*
    <?php if(!(Yii::app()->controller->id=='question' && Yii::app()->controller->action->id=='create')):?>
        <?php
        // выводим виджет с формой
            $this->widget('application.widgets.SimpleForm.SimpleForm', array());
        ?>  
    <?php endif;?>    
*/?>	
    <div id="footer">
        <div class='container'>
            <div class='row'>
                <div class='col-md-4 col-sm-4 center-align'>
                    <img src='/pics/2015/logo_inv.png' alt='Консультация юриста' class='center-block' />
                    <p>
                        <br />
                        100yuristov@mail.ru<br />
                        

                    </p>
                    <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki" data-yashareTheme="counter"></div>

                </div>
                <div class='col-md-8 col-sm-8'>
                    <div> <h3>Онлайн справочник кодексов Российской Федерации</h3>
                        <!--<ul id='footer-menu'>
                            <li><a href="http://www.codecs.100yuristov.com/apk-rf/" target="blank">АПК РФ</a></li>
							<li><a href="http://www.codecs.100yuristov.com/gk-rf/" target="blank">ГК РФ</a></li>
							<li><a href="http://www.codecs.100yuristov.com/gpk-rf/" target="blank">ГПК РФ</a></li>
							<li><a href="http://www.codecs.100yuristov.com/jk-rf/" target="blank">ЖК РФ</a></li>
							<li><a href="http://www.codecs.100yuristov.com/koap-rf/" target="blank">КоАП РФ</a></li>
							<li><a href="http://www.codecs.100yuristov.com/nk-rf/" target="blank">НК РФ</a></li>
							<li><a href="http://www.codecs.100yuristov.com/sk-rf/" target="blank">СК РФ</a></li>
							<li><a href="http://www.codecs.100yuristov.com/tk-rf/" target="blank">ТК РФ</a></li>
							<li><a href="http://www.codecs.100yuristov.com/uik-rf/" target="blank">УИК РФ</a></li>
							<li><a href="http://www.codecs.100yuristov.com/uk-rf/" target="blank">УК РФ</a></li>
							<li><a href="http://www.codecs.100yuristov.com/upk/" target="blank">УПК РФ</a></li>
                        </ul> -->
                    </div>
                    <p><noindex>
                        &copy; Информационно-правовой портал «100 Юристов» 2014. <br />
Все права, на любые материалы, размещенные на сайте, защищены в соответствии с российским и международным законодательством об авторском праве и смежных правах. При любом использовании текстовых, аудио-, видео- и фотоматериалов ссылка на www.100yuristov.com обязательна. Адрес электронной почты: 100yuristov@mail.ru. <a href="/">Юридическая консультация онлайн</a>
						</noindex>
                    </p>
                </div>
            </div>
        </div>
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
<noscript><div><img src="//mc.yandex.ru/watch/26550786" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
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


<script async="async" src="https://w.uptolike.com/widgets/v1/zp.js?pid=1438541" type="text/javascript"></script>

</body>
</html>