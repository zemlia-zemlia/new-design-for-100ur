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
    Yii::app()->clientScript->registerScriptFile("/js/respond.min.js");
    Yii::app()->clientScript->registerScriptFile("jquery.js",CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("/bootstrap/js/bootstrap.min.js",CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("/js/jquery.maskedinput.min.js",CClientScript::POS_END);
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
<meta name="c3b555200e34c47785fca1f69573ef95" content="">
</head>  

<body>
 <!-- 20a5d78ef98720e0 -->   
    <div id="top-menu-wrapper">
        <ul>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/')?CHtml::link('Главная', '/'):'<span class="active">Главная</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/konsultaciya_yurista_advokata/')?CHtml::link('Консультация', Yii::app()->createUrl('/site/konsultaciya_yurista_advokata')):'<span class="active">Консультация</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/partners/')?CHtml::link('Партнеры', Yii::app()->createUrl('/site/partners')):'<span class="active">Партнеры</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/contacts/')?CHtml::link('Контакты', Yii::app()->createUrl('/site/contacts')):'<span class="active">Контакты</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/blog/')?CHtml::link('Блог', Yii::app()->createUrl('/blog')):'<span class="active">Блог</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/question/')?CHtml::link('Все вопросы', Yii::app()->createUrl('question')):'<span class="active">Все вопросы</span>';?></li>
            <li><?php echo (!stristr($_SERVER['REQUEST_URI'], '/question/create/'))?CHtml::link('Задать вопрос', Yii::app()->createUrl('question/create',array('from'=>'top-menu'))):'<span class="active">Задать вопрос</span>';?></li>
        </ul>
    </div>
    
    <div id="header">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <br/>
                    <?php if($_SERVER['REQUEST_URI'] != '/'):?>
                    <a href="/">
                        <img src="/pics/2015/logo.png" alt="100 юристов" />
                    </a>
                    <?php else:?>
                        <img src="/pics/2015/logo.png" alt="100 юристов" />
                    <?php endif;?>                            
                    <div class="logo-description">
                        <b>Профессиональные юридические консультации</b>
						<br/>
                    </div>
                </div>
                <div class="col-md-5 col-sm-5">
					<br/> 
					<div class="header-phone"><b>Консультация юриста по телефону</b><br/> 
                    <b>(499) 322-45-41</b> - Москва <br/> 
					<b>(812) 309-68-26</b> - Санкт Петербург</div>
				</div>
                <div class="col-md-3 col-sm-3">
                    <div class="questions-counter">
                    <?php
                        $questionsCount = Question::getCountByStatus(Question::STATUS_PUBLISHED);
                        echo $questionsCount; 
                    ?>
                    </div>
					<br/>
                    <div class="questions-counter-description"><?php echo CustomFuncs::numForms($questionsCount, 'вопрос', "вопроса", "вопросов") ?> на сайте</div>
					
                   <!-- <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki" data-yashareTheme="counter"></div> -->
                   <?php if(!stristr($_SERVER['REQUEST_URI'], '/question/create/')):?> 
                   <?php echo CHtml::link('<b>ЗАДАТЬ СВОЙ ВОПРОС</b>', Yii::app()->createUrl('question/create'), array('class'=>'btn btn-block btn-danger')); ?>
                   <?php endif;?>
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
                    <h4 id="left-menu-switch">Категории</h4>
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
            <div class="col-md-7 col-sm-7">
                <?php echo $content;?>
                
                <?php if(!(Yii::app()->controller->id=='question' && Yii::app()->controller->action->id=='create')):?>
                    <?php
                    // выводим виджет с последними 4 вопросами
                        $this->widget('application.widgets.RecentPosts.RecentPosts', array());
                    ?>  
                <?php endif;?>
            </div>
				<div class="col-md-3 col-sm-3">
				<div style=" text-align:center; " >
					<h3><b>Консультация юриста по телефону</b></h3>
					<p style="font-size:27px;"> Москва и МО<br/> 
					<b>(499) 322-45-41</b> <br/> <br/>
					Санкт Петербург и ЛО<br/> 
					<b>(812) 309-68-26</b></p>  
				</div>
				<br/>
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
						<h3>Портал работает при поддержке:</h3>
						    <div class="col-md-12 col-xs-4">
                                <img class="img-responsive center-block" alt="Мониторинг правоприменения в сети" title="Мониторинг правоприменения в сети" src="/pics/monitoring.png"> 
                            </div>
                            <div class="col-md-6 col-xs-4">
                                <img class="img-responsive center-block" alt="Правительство Москвы" title="Правительство Москвы" src="/pics/pravitelstvo.png"> 
                            </div>
                            <div class="col-md-6 col-xs-4">
                                <img class="img-responsive center-block" alt="Министерство Юстиции" title="Министерство Юстиции" src="/pics/minyust.png"> 
                            </div>
                            <div class="col-md-6 col-xs-4">
                                <img class="img-responsive center-block" alt="Дума Российской Федерации" title="Дума Российской Федерации" src="/pics/duma.png"> 
                            </div>
                            <div class="col-md-6 col-xs-4">
                                <img class="img-responsive center-block" alt="Федеральная служба по контролю за наркотиками" title="Федеральная служба по контролю за наркотиками" src="/pics/fskn.png"> 
                            </div>
                            <div class="col-md-6 col-xs-4">
                                <img class="img-responsive center-block" alt="" title="" src="/pics/tpp.png"> 
                            </div>
                            <div class="col-md-6 col-xs-4">
                                <img class="img-responsive center-block" alt="" title="" src="/pics/cs.png"> 
                            </div>
							<div class="col-md-12 col-xs-4">
                                <img class="img-responsive center-block" alt="" title="" src="/pics/lr.jpg"> 
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
                <div class='col-md-3 col-sm-3 center-align'>
                    <img src='/pics/2015/logo_inv.png' alt='Консультация юриста' class='center-block' />
                    
                    <div class="vcard">
                    <div>
                      <span class="category">Правовой портал</span>
                      <span class="fn org">100 Юристов</span>
                    </div>
                    <div class="adr">
                      <span class="locality">г. Москва</span>,
                      <span class="street-address">Кожевническая ул., д.10, стр 1</span>
                    </div>
                    <div>Телефон: <span class="tel">+7 (499) 322-45-41</span> - Москва</div>
                    <div><span class="tel">+7 (812) 309-68-26</span> - Санкт Петербург</div>
                    <div>100yuristov@mail.ru</div>
                    <div>Мы работаем <span class="workhours">ежедневно с 00:00 до 24:00</span>
                      <span class="url">
                        <span class="value-title" title="http://www.100yuristov.com"> </span>
                      </span>
                    </div>
                   </div>
                    <noindex>
                    <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script><div class="yashare-auto-init" data-yashareL10n="ru" data-yashareType="small" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki" data-yashareTheme="counter"></div>
					</noindex>
                    
                </div>
                
                <div class='col-md-3 col-sm-3 center-align'>
					<noindex>
                    <script type="text/javascript" src="//vk.com/js/api/openapi.js?121"></script>

                    <!-- VK Widget -->
                    <div id="vk_groups"></div>
                    <script type="text/javascript">
                    VK.Widgets.Group("vk_groups", {mode: 0, width: "260", height: "200", color1: 'FFFFFF', color2: '2B587A', color3: '5B7FA6'}, 78448546);
                    </script>
					</noindex>
                </div>
				                
				<div class='col-md-3 col-sm-3 center-align'>
				<br/><noindex>
                <div id="ok_group_widget"></div>
					<script>
					!function (d, id, did, st) {
					  var js = d.createElement("script");
					  js.src = "https://connect.ok.ru/connect.js";
					  js.onload = js.onreadystatechange = function () {
					  if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
						if (!this.executed) {
						  this.executed = true;
						  setTimeout(function () {
							OK.CONNECT.insertGroupWidget(id,did,st);
						  }, 0);
						}
					  }}
					  d.documentElement.appendChild(js);
					}(document,"ok_group_widget","53087450366125","{width:260,height:200}");
					</script>
					</noindex>
                </div>

                <div class='col-md-3 col-sm-3'>
				<small>
                    <p style="text-align: justify;"> <noindex>
                        &copy; Правовой портал «100 Юристов» 2014. <br />
                            Все права, на любые материалы, размещенные на сайте, защищены в соответствии с российским и международным законодательством об авторском праве и смежных правах. При любом использовании текстовых, аудио-, видео- и фотоматериалов ссылка на www.100yuristov.com обязательна. Адрес электронной почты: 100yuristov@mail.ru. 
                            <?php if($_SERVER['REQUEST_URI'] != '/'):?>
                            <a href="/">Задать вопрос юристу онлайн</a>
                            <?php endif;?>
                        </noindex>
                    </p>
				</small>
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