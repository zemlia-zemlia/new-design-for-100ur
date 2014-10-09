<!doctype html>
<html lang="ru">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="ru" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php 
    Yii::app()->clientScript->registerCssFile("/bootstrap3/css/bootstrap.min.css");
    Yii::app()->clientScript->registerCssFile("/css/style.css");

    
    Yii::app()->clientScript->registerScriptFile("jquery.js");
    Yii::app()->clientScript->registerScriptFile("/bootstrap3/js/bootstrap.min.js");
    Yii::app()->clientScript->registerScriptFile("/js/scripts.js");
    Yii::app()->clientScript->registerScriptFile("/js/jquery.placeholder.min.js");
    
?>

</head>  

<body>
    <div id="header">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="logo-text"><a href="/">100 Юристов!</a></div>
                    <div class="logo-description">отвечают на ваши вопросы</div>
                </div>
                <div class="col-md-4 col-sm-4 center-align">
                    <div class="header-phone">8 (499) 301-00-35</div>
                    <div>Получите консультацию юриста по телефону сейчас</div>
					<div>Москва и МО (ежедневно) с 10.00 до 19.00 </div>
                </div>
                <div class="col-md-4 col-sm-4 center-align">
                    <div class="questions-counter">
                    <?php
                        $questionsCount = Question::getCountByStatus(Question::STATUS_PUBLISHED);
                        echo $questionsCount; 
                    ?>
                    </div>
                    <div><?php echo CustomFuncs::numForms($questionsCount, 'вопрос', "вопроса", "вопросов") ?> на сайте</div>
                </div>
            </div>
        </div>
        
    </div>
    
    <div id="top-menu">
        <ul>
            <li><?php echo CHtml::link('<span class="glyphicon glyphicon-home"></span> Главная', '/');?></li>
            <li><?php echo CHtml::link('<span class="glyphicon glyphicon-heart-empty"></span> Партнеры', Yii::app()->createUrl('/site/partners'));?></li>
            <li><?php echo CHtml::link('<span class="glyphicon glyphicon-envelope"></span> Контакты', Yii::app()->createUrl('/site/contacts'));?></li>
            <li><?php echo CHtml::link('<span class="glyphicon glyphicon-question-sign"></span> Все вопросы', Yii::app()->createUrl('question'));?></li>
        </ul>
        <div class="clearfix"></div>
    </div>
    
    <div id="middle">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div id="left-bar">
                        <h4>Категории вопросов</h4>
                        <?php
                        // выводим виджет с деревом категорий
                            $this->widget('application.widgets.CategoriesTree.CategoriesTree', array());
                        ?>
                    </div>
                </div>

                <div class="col-md-8 col-sm-8">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </div>
  
    <div id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    &copy; 100 юристов 2014 <br/>
					Общество с ограниченной ответственностью «100 Юристов»
					ОГРН 114774637014 ИНН 7705358485 КПП 770501001 Юридический адрес: г. Москва, ул. Кожевническая, д. 10, стр. 1 
                </div>
                <div class="col-md-4 col-sm-4">
                    
                </div>
                <div class="col-md-4 col-sm-4">
                    
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

</body>
</html>