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
    
    <div id="middle">
        <div class="container-fluid">
            
            <?php echo $content; ?>

        </div>
    </div>
    
    
</body>
</html>