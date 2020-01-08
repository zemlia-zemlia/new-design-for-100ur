<!doctype html>
<html lang="ru">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="ru" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php 
    Yii::app()->clientScript->registerCssFile("/bootstrap/css/bootstrap.min.css");
    Yii::app()->clientScript->registerCssFile("/css/style.css");

    
    Yii::app()->clientScript->registerScriptFile("jquery.js");
    Yii::app()->clientScript->registerScriptFile("/bootstrap/js/bootstrap.min.js");
    Yii::app()->clientScript->registerScriptFile("/js/scripts.js");
    Yii::app()->clientScript->registerScriptFile("/js/jquery.placeholder.min.js");
    
?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-5130234-30');
    <?php if(Yii::app()->user->id>0) {
      echo ", { 'userId': '" . Yii::app()->user->id . "' }";
    }
    ?>
  
  ga('send', 'pageview');

</script>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter27876861 = new Ya.Metrika({id:27876861,
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
<noscript><div><img src="//mc.yandex.ru/watch/27876861" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

</head>  

<body>
    
    <div id="middle">
        <div class="container-fluid">
            
            <?php echo $content; ?>

        </div>
    </div>
    
    
</body>
</html>