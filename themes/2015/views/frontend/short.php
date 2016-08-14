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
    Yii::app()->clientScript->registerCssFile('/css/2015/jquery-ui.css');
    Yii::app()->clientScript->registerScriptFile('/js/jquery-ui.min.js');
    
    Yii::app()->clientScript->registerScriptFile("/js/scripts.js");
    Yii::app()->clientScript->registerScriptFile("/js/jquery.placeholder.min.js",CClientScript::POS_END);

?>
</head>  

<body>
    <div id="top-menu-wrapper">
        <ul>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/')?CHtml::link('Главная', '/'):'<span class="active">Главная</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/konsultaciya_yurista_advokata/')?CHtml::link('Консультация', Yii::app()->createUrl('/site/konsultaciya_yurista_advokata')):'<span class="active">Консультация</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/partners/')?CHtml::link('Партнеры', Yii::app()->createUrl('/site/partners')):'<span class="active">Партнеры</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/company/')?CHtml::link('Компании', Yii::app()->createUrl('/company/')):'<span class="active">Компании</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/region/')?CHtml::link('Регионы', Yii::app()->createUrl('/region/')):'<span class="active">Регионы</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/contacts/')?CHtml::link('Офисы', Yii::app()->createUrl('/site/contacts')):'<span class="active">Офисы</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/blog/')?CHtml::link('Блог', Yii::app()->createUrl('/blog')):'<span class="active">Блог</span>';?></li>
            <li><?php echo ($_SERVER['REQUEST_URI'] != '/question/')?CHtml::link('Направления', Yii::app()->createUrl('question')):'<span class="active">Направления</span>';?></li>
            <li><?php echo (!stristr($_SERVER['REQUEST_URI'], '/question/create/'))?CHtml::link('Задать вопрос', Yii::app()->createUrl('question/create',array('from'=>'top-menu'))):'<span class="active">Задать вопрос</span>';?></li>
            <?php if(Yii::app()->user->checkAccess(User::ROLE_OPERATOR) || Yii::app()->user->checkAccess(User::ROLE_JURIST)):?>
            <li>    
                <?php echo CHtml::ajaxLink("<span class='glyphicon glyphicon-refresh'></span>", Yii::app()->createUrl('site/clearCache'), array(
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
    
    <div id="header">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <?php if($_SERVER['REQUEST_URI'] != '/'):?>
                    <a href="/">
                        <img src="/pics/2015/logo.png" alt="100 юристов" />
                    </a>
                    <?php else:?>
                        <img src="/pics/2015/logo.png" alt="100 юристов" />
                    <?php endif;?>      
						<h5>ЮРИДИЧЕСКИЕ КОНСУЛЬТАЦИИ ОНЛАЙН</h5>
                    <!--  <div class="logo-description">
                        
                    </div> -->   
                </div>
                
                <?php if(Yii::app()->user->isGuest):?>
                        <div class="col-md-8 col-sm-8" style="text-align: right;">
                            <div class="header-phone"><b><span class="glyphicon glyphicon-phone-alt"></span> Консультации по телефону</b><br/> 
                                    Москва <b>7 (499) 301-00-44</b><br/> 
                                    Санкт Петербург <b>7 (812) 309-68-26</b><br/> 
                                    Нижний Новгород <b>7 (831) 280-99-42</b>
                            </div>
                            </div>
                        
                    <?php else:?>
                                            
                        <div class="alert alert-info col-md-2 col-sm-2 col-sm-offset-6 col-md-offset-6" style="text-align: right;">             
                        <?php
                            // выводим виджет с формой логина
                            $this->widget('application.widgets.Login.LoginWidget', array(
                            ));
                        ?>
                        </div>
                    <?php endif;?>
            </div>
            
        </div>
    </div>
    
        
    <div id="middle">
        <div class="container">
                
			
            <div class="col-md-2 col-sm-2"   id="left-panel">
			
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php
                                $questionsCountInt = Question::getCount()*2;
                                $questionsCount = str_pad((string)$questionsCountInt,6, '0',STR_PAD_LEFT);
                                $numbers = str_split($questionsCount);
                                $answersCount = str_pad((string)round($questionsCountInt*1.684),6, '0',STR_PAD_LEFT);;
                                $numbersAnswers = str_split($answersCount);
                            ?>
                            <div class="questions-counter-description">
                                <div class=" center-align">
                                    <div>За время работы портала задано</div>
                                    <p class="kpi-counter">
                                        <?php foreach($numbers as $num):?><span><?php echo $num;?></span><?php endforeach;?><br />
                                        вопросов
                                    </p>
                                    <div>На них дано</div>
                                    <p class="kpi-counter">
                                        <?php foreach($numbersAnswers as $num):?><span><?php echo $num;?></span><?php endforeach;?><br />
                                        ответов
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
					
                <div class="panel panel-default">
                    <div class="panel-body">
					<div style=" text-align:center; " >
					<p style="font-size:16px;"><b>Вы также можете;</b></p>
					<?php echo CHtml::link('<b>НАНЯТЬ</b>', Yii::app()->createUrl('/question/call/'), array('class'=>'btn btn-block btn-warning')); ?> 
					<p style="font-size:14px;"><b>Юриста</b> или <b>Адвоката</b> Для сопровождения сделки, состаления документов, представления интересов в судах, организациях и т.д.</p>
					</div>
                    </div>
                </div>
                
			<div id="left-bar" class="panel gray-panel" >
				<h4 id="left-menu-switch">Категории</h4>
					
						<?php
						// выводим виджет с деревом категорий
							$this->widget('application.widgets.CategoriesTree.CategoriesTree', array());
						?>
					
			</div>	
			
			
			
				<div class='panel panel-default' style="text-align:center;">
					<div class='row'>
						<div class='col-md-12 col-sm-12'>
							<img src='/pics/2015/thumb_up_orange.png' alt='ВЫСОКОЕ КАЧЕСТВО' class='center-block' />
							<h5>ВЫСОКОЕ КАЧЕСТВО</h5>
							<p>
								Все сертифицированные юристы проекта проходят обязательную проверку квалификации.
							</p>
						</div>
						<div class='col-md-12 col-sm-12'>
							<img src='/pics/2015/clock_orange.png' alt='ЭКОНОМИЯ ВРЕМЕНИ' class='center-block' />
							<h5>ЭКОНОМИЯ ВРЕМЕНИ</h5>
							<p>
								Вы получаете ответ  на свой
								вопрос в максимально 
								сжатые сроки.
							</p>
							
						</div>
						<div class='col-md-12 col-sm-12'>
							<img src='/pics/2015/shield_orange.png' alt='КОНФИДЕНЦИАЛЬНОСТЬ' class='center-block' />
							<h5>КОНФИДЕНЦИАЛЬНО</h5>
							<p>
								Ваши персональные данные нигде не публикуются.
							</p>
						</div>
					</div>
				</div>
				
			
				
				
            </div>
            <div class="col-md-7 col-sm-7" id="center-panel">
                <?php echo $content;?>

            </div>
            <div class="col-md-3 col-sm-3"   id="right-panel">
                <div class="panel orange-panel" style="text-align:center;">	
                        <script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
                        <script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
                        <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,linkedin,lj"></div>
                </div>	
                
                <?php if(Yii::app()->user->role == User::ROLE_JURIST || Yii::app()->user->role == User::ROLE_OPERATOR):?>
                <div class="panel gray-panel">
                    <div class="panel-body">                    
                        <?php
                            // выводим виджет с поиском вопросов
                            $this->widget('application.widgets.SearchQuestions.SearchQuestionsWidget', array(
                            ));
                        ?>
                    </div>
                </div>
                <?php endif;?>
                
                <?php if(Yii::app()->user->isGuest):?>
                <div class="panel gray-panel">
                    <div class="panel-body">                    
                        <?php
                            // выводим виджет с формой логина
                            $this->widget('application.widgets.Login.LoginWidget', array(
                            ));
                        ?>
                    </div>
                </div>
                <?php endif;?>

                <div class="panel orange-panel">
                    <div class="panel-body">
                        <div style="text-align:center; " >
                                <h4><span class="glyphicon glyphicon-phone-alt"></span> Консультации по телефону для жителей:</h4>
                                <hr/>
                                <p style="font-size:17px;"> Москвы и МО<br/> 
                                <b>7 (499) 301-00-44</b></p> 
                                <hr/>
                                <p style="font-size:17px;"> Санкт Петербурга и ЛО<br/> 
                                <b>7 (812) 309-68-26</b></p> 
								<hr/>
                                <p style="font-size:17px;"> Нижний Новгород<br/> 
                                <b>7 (831) 280-99-42</b></p> 			
                        </div>
                    </div>
                </div>
                				
                <div class="panel gray-panel">
                    <div class="panel-body">
					<div style=" text-align:center; " >
					<p style="font-size:22px;"><b>Не хотите искать?</b></p>
					<?php echo CHtml::link('<b>ОСТАВЬТЕ ЗАЯВКУ</b>', Yii::app()->createUrl('/question/call/'), array('class'=>'btn btn-block btn-info')); ?> <br/>
					<p style="font-size:24px;">ЮРИСТЫ <b>САМИ</b><br/>ВАМ ПОЗВОНЯТ!</p>
					</div>
                    </div>
                </div>
		
                <div class="panel gray-panel">
                    <div class="panel-body">                    
                        <?php
                            // выводим виджет с последними ответами
                            $this->widget('application.widgets.RecentAnswers.RecentAnswers', array(
                            ));
                        ?>
                    </div>
                </div>
				

                                              
                <div class="panel gray-panel">
                    <div class="panel-body">
                        <div class="row">
						<h4>Работаем при поддержке:</h4>
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
                    <small>
                    <div class="vcard">
                    <div>
                      <span class="category">Правовой портал</span>
                      <span class="fn org">100 Юристов</span>
                    </div> 
                    <div class="adr">
                      <span class="locality">г. Москва</span>,
                      <span class="street-address">Шлюзовая набережная, д.6, стр 4</span>
                    </div>
                    <div>Телефон: <span class="tel">+7 (499) 301-00-44</span> - Москва</div>
                    <div><span class="tel">+7 (812) 309-68-26</span> - Санкт Петербург</div>
                    <div>admin@100yuristov.com</div>
                    <div>Мы работаем <span class="workhours">ежедневно с 00:00 до 24:00</span>
                      <span class="url">
                        <span class="value-title" title="http://www.100yuristov.com"> </span>
                      </span>
                    </div>
                  </div>
					</small>
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
				
                    <p style="text-align: justify;"> <noindex>
                        <small>
                        &copy; Правовой портал «100 Юристов» 2014. <br />
                            Все права, на любые материалы, размещенные на сайте, защищены в соответствии с российским и международным законодательством об авторском праве и смежных правах. При любом использовании текстовых, аудио-, видео- и фотоматериалов ссылка на www.100yuristov.com обязательна. Адрес электронной почты: 100yuristov@mail.ru. 
                            <?php if($_SERVER['REQUEST_URI'] != '/'):?>
                            <a href="/">Задать вопрос юристу онлайн</a>
                            <?php endif;?>
                        </small>    
                        </noindex>
                    </p>
				
                </div>
            </div>
        </div>
    </div>
    
<?php if($_SERVER['REMOTE_ADDR'] != '127.0.0.1'):?>  
 
 <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-56588534-1', 'auto');
  ga('send', 'pageview');

</script>

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
<?php endif;?>

</body>
</html>