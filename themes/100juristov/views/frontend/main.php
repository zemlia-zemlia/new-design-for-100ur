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
                    <div class="logo-text">100 юристов</div>
                    <div class="logo-description">отвечают на ваши вопросы</div>
                </div>
                <div class="col-md-4 col-sm-4 center-align">
                    <div class="header-phone">(495) 249-90-04</div>
                    <div>Получите консультацию сейчас</div>
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
                    &copy; 100 юристов 2014
                </div>
                <div class="col-md-4 col-sm-4">
                    
                </div>
                <div class="col-md-4 col-sm-4">
                    
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>