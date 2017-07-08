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
    Yii::app()->clientScript->registerScriptFile("/bootstrap/js/bootstrap.min.js",CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("/js/jquery.maskedinput.min.js",CClientScript::POS_END);
    Yii::app()->clientScript->registerCssFile('/css/2017/jquery-ui.css');
    Yii::app()->clientScript->registerScriptFile('/js/jquery-ui.min.js');
    
    Yii::app()->clientScript->registerScriptFile("/js/scripts.js");
    Yii::app()->clientScript->registerScriptFile("/js/jquery.placeholder.min.js",CClientScript::POS_END);

?>
</head>  

<body>
        <div id="header">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3 center-align">
                    <div class="logo-wrapper">
                        <?php if($_SERVER['REQUEST_URI'] != '/cabinet/'):?>
                        <a href="/cabinet/">
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
    
        
    <div class="top-form-replace"></div>
    
    <div id="middle ">
        <div class="container-fluid container">
                
			
            <div class="col-md-3 col-sm-4">
                <h1 class="vert-margin20">Мои кампании</h1>	
				<p>
                <?php $campaigns = Campaign::getCampaignsForBuyer(Yii::app()->user->id);?>
                
                <?php echo CHtml::link('Создать кампанию', Yii::app()->createUrl('campaign/create'), array('class' => 'btn btn-primary btn-block'));?>
                
                
                <?php foreach($campaigns as $campaign):?>
				</p>
                <div class="flat-panel" >
                    <div class="inside">
                        <h4>
                            <?php echo CHtml::link($campaign->region->name . ' ' . $campaign->town->name, Yii::app()->createUrl('/cabinet/leads', array('campaign'=>$campaign->id)));?>
                            
                            <?php if($campaign->active != Campaign::ACTIVE_MODERATION):?>
                                <?php echo $campaign->price;?> руб.
                            <?php endif;?>
                            
                            <?php echo CHtml::link("<span class='glyphicon glyphicon-cog'></span>", Yii::app()->createUrl('/cabinet/campaign', array('id'=>$campaign->id)));?>    
                        </h4>
                        <?php if($campaign->active != Campaign::ACTIVE_YES):?>
                        <p class="text-center">                            
                            <?php echo $campaign->getActiveStatusName();?>
                        </p>
                        <?php endif;?>

                    </div>
                </div>	
                <?php endforeach;?>
            </div>
            
            <div class="flat-panel inside col-md-9 col-sm-8">
                <?php echo $content;?>
            </div>
            				
        </div>
     </div>
    
    <div id="footer" class="container">
        <div >
            <div class="text-center">
                <p><strong>Возникли вопросы?</strong><br /> Задайте их техподдержке: admin@100yuristov.com, ответим оперативно.</p>
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