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
</head>  

<body>
    
        <div id="header">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3 center-align">
                    <div class="logo-wrapper">
                        <?php if($_SERVER['REQUEST_URI'] != '/'):?>
                        <a href="/">
                            <img src="/pics/2017/logo_white.png" alt="100 Юристов и Адвокатов" title="Юридический портал"/>
                        </a>
                        <?php else:?>
                            <img src="/pics/2017/logo_white.png" alt="100 Юристов и Адвокатов" title="Юридический портал" />
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
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/konsultaciya_yurista_advokata/')?CHtml::link('Вопрос юристу', Yii::app()->createUrl('/site/konsultaciya_yurista_advokata')):'<span class="active">Вопрос юристу</span>';?></li>
			<li><?php echo ($_SERVER['REQUEST_URI'] != '/yurist/')?CHtml::link('Юристы', Yii::app()->createUrl('/yurist/')):'<span class="active">Юристы</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/region/')?CHtml::link('Регионы', Yii::app()->createUrl('/region/')):'<span class="active">Регионы</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/contacts/')?CHtml::link('Филиалы', Yii::app()->createUrl('/site/contacts')):'<span class="active">Филиалы</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/blog/')?CHtml::link('Блог', Yii::app()->createUrl('/blog')):'<span class="active">Блог</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/q/')?CHtml::link('Вопросы', Yii::app()->createUrl('/question/index')):'<span class="active">Вопросы</span>';?></li> 			
			<li><?php echo ($_SERVER['REQUEST_URI'] != '/site/goryachaya_liniya/')?CHtml::link('8-800', Yii::app()->createUrl('/site/goryachaya_liniya/')):'<span class="active">8-800</span>';?></li>
			<li><?php echo (!stristr($_SERVER['REQUEST_URI'], '/question/create/'))?CHtml::link('Задать вопрос', Yii::app()->createUrl('question/create',array('from'=>'top-menu'))):'<span class="active">Задать вопрос</span>';?></li>
	
			
			
			
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

    
    <?php if((Yii::app()->user->isGuest && !(Yii::app()->controller->id=='question' && Yii::app()->controller->action->id=='create'))):?>
        <?php
        // выводим виджет с формой
            $this->widget('application.widgets.SimpleForm.SimpleForm', array());
        ?> 
    
    <?php else:?>
        <div class="top-form-replace"></div>
    <?php endif;?>
 
    <div id="middle">
        <div class="container">
		<?php if(Yii::app()->user->role != User::ROLE_JURIST && Yii::app()->user->role != User::ROLE_OPERATOR):?>
            <div id="ctas" class="hidden-sm hidden-xs">
                <div class="row">
                    <div class="col-md-3 col-sm-3 cta-item" style="background-color: #39a6bd;">
                        <div class="cta-text">
                        <p style="font-size: 20px; margin: 10px;" class="text-uppercase">Вопрос юристу</p>
                        <p class="small">Задайте любой вопрос специалистам, и в течение 15 минут вы получите ответы наших юристов.</p>
                        </div>
                        <?php echo CHtml::link("<span class='yur-icon yur-icon-question'></span> Задать вопрос &nbsp;<img src='/pics/2017/arrow_list_blue.png' alt='100 Юристов и Адвокатов' />", Yii::app()->createUrl('question/create/?utm_source=100yuristov&utm_campaign=yuristi&utm_medium=button&utm_content=vopros'), array('class'=>'button button-white-gradient btn-block')); ?>
                    </div>
                    
                    <div class="col-md-3 col-sm-3 cta-item" style="background-color: #1d979b;">
                        <div class="cta-text">
                        <p style="font-size: 20px; margin: 10px;" class="text-uppercase">Звонок юриста</p>
                        <p class="small">Оставьте номер телефона, и наш юрист свяжется с вами, чтобы проконсультировать вас.</p>
                        </div>
                        <?php echo CHtml::link("<span class='yur-icon yur-icon-call'></span> Заказать звонок &nbsp;<img src='/pics/2017/arrow_list_blue.png'  alt='100 Юристов и Адвокатов' />", Yii::app()->createUrl('question/call'), array('class'=>'button button-white-gradient btn-block')); ?>
                    </div>
                        
                    <div class="col-md-3 col-sm-3 cta-item" style="background-color: #18668b;">
                        <div class="cta-text">
                        <p style="font-size: 20px; margin: 10px;" class="text-uppercase">Документы online</p>
                        <p class="small">Закажите документ, и наши юристы его подготовят для вас.</p>
                        </div>
                        <?php echo CHtml::link("<span class='yur-icon yur-icon-doc'></span> Заказать документ &nbsp;<img src='/pics/2017/arrow_list_blue.png' alt='100 Юристов и Адвокатов'/>", Yii::app()->createUrl('question/docs'), array('class'=>'button button-white-gradient btn-block')); ?>
                    </div>
                    
                    <div class="col-md-3 col-sm-3 cta-item" style="background-color: #206cbb;">
                        <div class="cta-text">
                        <p style="font-size: 20px; margin: 10px;" class="text-uppercase">Услуги</p>
                        <p class="small">Заключить договор для сопровождения сделки, представления интересов в судах, организациях и т.д.</p>
                        </div>
                        <?php echo CHtml::link("<span class='yur-icon yur-icon-service'></span> Получить услуги &nbsp;<img src='/pics/2017/arrow_list_blue.png' alt='100 Юристов и Адвокатов' />", Yii::app()->createUrl('question/services'), array('class'=>'button button-white-gradient btn-block')); ?>
                    </div>
                </div>
            </div>
            
            <?php else:?>
                <div class="cta-replace"></div>
            <?php endif;?>
            
            <div class="row">
                
            <div class="col-sm-6 col-sm-push-3 col-md-6 col-md-push-3" id="center-panel">
                <?php echo $content;?>
            </div>    
                
            <div class="col-sm-3 col-sm-pull-6 col-md-3 col-md-pull-6" id="left-panel">
                
                <?php if(Yii::app()->user->isGuest):?>
                    <?php
                        // выводим виджет Назойливый
                        $this->widget('application.widgets.Annoying.AnnoyingWidget', array(
                            'showAlways' => true,
                        ));
                    ?>
                <?php endif;?>
                
                
		<?php if(Yii::app()->user->role != User::ROLE_JURIST):?>	
                <div class="flat-panel vert-margin20">
                        <?php
                            $questionsCountInt = Question::getCount()*2;
                            $questionsCount = str_pad((string)$questionsCountInt,6, '0',STR_PAD_LEFT);
                            $numbers = str_split($questionsCount);
                            $answersCount = str_pad((string)round($questionsCountInt*1.684),6, '0',STR_PAD_LEFT);;
                            $numbersAnswers = str_split($answersCount);
                        ?>
                        <div class="questions-counter-description">
                            <div class="center-align">
                                <div class="header-block-grey">За время работы портала</div>
                                <div class="header-block-grey-arrow"></div>
                                <p>Задано вопросов</p>
                                <p class="kpi-counter">
                                    <img src="/pics/2017/icon_question.png" alt="100 Юристов и Адвокатов" title="Юридический портал" />
                                    <?php foreach($numbers as $num):?><span><?php echo $num;?></span><?php endforeach;?><br />
                                </p>
                                <div>На них дано ответов</div>
                                <p class="kpi-counter">
                                    <img src="/pics/2017/icon_answer.png" alt="100 Юристов и Адвокатов" title="Юридический портал" />
                                    <?php foreach($numbersAnswers as $num):?><span><?php echo $num;?></span><?php endforeach;?><br />
                                </p>
                                <p>по ТЕМАМ</p>
                                <img src="/pics/2017/arrow_down.png" alt="100 Юристов и Адвокатов" title="Юридический портал" class="center-block" />
                            </div>
                        </div>
                </div>
                <?php endif;?>
				
                <div id="left-bar" class="">
					<h4 id="left-menu-switch" class="header-bordered">Темы вопросов</h4>

                    <?php
                    // выводим виджет с деревом категорий
                            $this->widget('application.widgets.CategoriesTree.CategoriesTree', array());
                    ?>

                </div>
                
                <div>
                    <?php
                    // выводим виджет с последними 4 вопросами
                        $this->widget('application.widgets.RecentPosts.RecentPosts', array('template'=>'panel'));
                    ?> 
                </div>

            </div>
            
            <div class="col-md-3 col-sm-3"  id="right-panel">
			                
                <?php if(Yii::app()->user->role == User::ROLE_JURIST):?>
                
                <div class="vert-margin20">           
                        <?php
                            // выводим виджет со статистикой ответов
                            $this->widget('application.widgets.MyAnswers.MyAnswers', array(
                            ));
                        ?>
                        </div>        
                
                        <div class="vert-margin20">          
                        <?php
                            // выводим виджет с поиском вопросов
                            $this->widget('application.widgets.SearchQuestions.SearchQuestionsWidget', array(
                            ));
                        ?>
                        </div> 
                <?php endif;?>
                
                
                    <?php if(Yii::app()->user->isGuest):?>
                        <div class="vert-margin20">
                            <?php
                                // выводим виджет с формой логина
                                $this->widget('application.widgets.Login.LoginWidget', array(
                                ));
                            ?>
                        </div>
                    <?php endif;?>
                
                
             	<?php if(Yii::app()->user->isGuest):?>			
                    <div class="">
                        <?php echo CHtml::link("<img src='/pics/2017/yurist_call.jpg' alt='100 Юристов и Адвокатов' title='Юридический портал' class='center-block' />", Yii::app()->createUrl('/question/call/')); ?> <br/>
                    </div>
                <?php endif;?>
		
                <div class="vert-margin20 flat-panel">   
                    <?php
                        // выводим виджет с последними ответами
                        $this->widget('application.widgets.RecentAnswers.RecentAnswers', array(
                        ));
                    ?>
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
                    <img src='/pics/2017/logo_dark.png' alt="100 Юристов и Адвокатов" title="Юридический портал" class='center-block' />
                    <div class="logo-description-footer">
                    <h5>юридические консультации онлайн</h5>    
                    </div>
                    <div id="social-icons-container">
                        <noindex>
                        <a href="https://www.instagram.com/100yuristov/" target="_blank" rel="nofollow"><img src="/pics/2017/ig_icon.png" alt="Instagram" /></a>
                        <a href="https://vk.com/sto_yuristov" target="_blank" rel="nofollow"><img src="/pics/2017/vk_icon.png" alt="VK" /></a>
                        <a href="#" rel="nofollow"><img src="/pics/2017/ok_icon.png" alt="Одноклассники" /></a>
                        <a href="#" rel="nofollow"><img src="/pics/2017/yt_icon.png" alt="Youtube" /></a>
                        <a href="#" rel="nofollow"><img src="/pics/2017/fb_icon.png" alt="Facebook" /></a>
                        <a href="https://twitter.com/stoyuristov" target="_blank" rel="nofollow"><img src="/pics/2017/tw_icon.png" alt="Twitter" /></a>
                        </noindex>
                    </div>
                </div>
				
				<div class='col-md-3 col-sm-3'>
		<small>
				<ul>
					<li><?php echo ($_SERVER['REQUEST_URI'] != '/site/offer/')?CHtml::link('Пользовательское соглашение', Yii::app()->createUrl('/site/offer/')):'<span class="active"><p>Пользовательское соглашение</p></span>';?></li>
					<li><?php echo ($_SERVER['REQUEST_URI'] != '/site/crm/')?CHtml::link('CRM Для юридических фирм', Yii::app()->createUrl('/site/crm/')):'<span class="active"><p>CRM Для юридических фирм</p></span>';?></li>
					<li><?php echo ($_SERVER['REQUEST_URI'] != '/site/lead/')?CHtml::link('Лиды', Yii::app()->createUrl('/site/lead/')):'<span class="active"><p>Лиды</p></span>';?></li>
					<li><?php echo ($_SERVER['REQUEST_URI'] != '/site/klienti_dlya_yuristov/')?CHtml::link('Клиенты юристам', Yii::app()->createUrl('/site/klienti_dlya_yuristov/')):'<span class="active"><p>Клиенты юристам</p></span>';?></li>
					<li><?php echo ($_SERVER['REQUEST_URI'] != '/site/about/')?CHtml::link('О проекте', Yii::app()->createUrl('/site/about/')):'<span class="active"><p>О проекте</p></span>';?></li>
					<li><?php echo ($_SERVER['REQUEST_URI'] != '/company/')?CHtml::link('Каталог компаний России', Yii::app()->createUrl('/company/')):'<span class="active"><p>Каталог компаний</p></span>';?></li>
				</ul>
		</small>
				</div>

                <div class='col-md-3 col-sm-3 small'>
					<div itemscope itemtype="http://schema.org/Organization"> 
						<span itemprop="name">100 Юристов</span>
							<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
							<span itemprop="addressLocality">Москва</span> 			<span itemprop="streetAddress">Шлюзовая набережная д.6 стр.4</span><br/>
							<span itemprop="addressLocality">Санкт-Петербург</span> <span itemprop="streetAddress">Ул. Достоевского д.25</span><br/>
							<span itemprop="addressLocality">Нижний Новгород</span> <span itemprop="streetAddress">Ул. Новая, д. 28</span><br/>
							<span itemprop="addressLocality">Екатеринбург</span>	<span itemprop="streetAddress">Ул. 8 Марта, д. 142</span><br/>
							<span itemprop="addressLocality">Ростов-на-Дону</span> 	<span itemprop="streetAddress">Ул. Красноармейская, д. 142/50</span><br/>
							<span itemprop="addressLocality">Краснодар</span> 		<span itemprop="streetAddress">Ул. Московская, 148</span><br/>
							<span itemprop="telephone">8-800-500-61-85</span>
							</div>
					</div>
				</div>
               				
				<div class='col-md-3 col-sm-3' style="text-align: center">
					<div class="header-phone">
                                <strong>Горячая линия</strong> <br/>юридических консультаций
					</div>
					<span class="header-phone-800">
						<strong>
									 8-800-500-61-85 
						</strong>
					</span>
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
<noscript><div><img src="//mc.yandex.ru/watch/26550786" style="position:absolute; left:-9999px;" alt="100 Юристов и Адвокатов" title="Юридический портал" /></div></noscript>
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
<!-- new hosting! -->
</body>
</html>