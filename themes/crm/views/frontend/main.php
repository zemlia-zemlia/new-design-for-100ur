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
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <a class="navbar-brand" href="/">Система работы с клиентами</a>
          </div>
          <p class="navbar-text navbar-right">
              <?php if(!Yii::app()->user->isGuest):?>
              <span class="glyphicon glyphicon-user"></span> <?php echo CHtml::link(CHtml::encode(Yii::app()->user->name),Yii::app()->createUrl('user')); ?> 
              <?php echo Yii::app()->user->roleName;?> &nbsp;
              <span class="glyphicon glyphicon-log-out"></span> <?php echo CHtml::link('Выход', Yii::app()->createUrl('site/logout'));?>
              <?php endif;?>
          </p>

        </div>
    </nav>
    
    <div id="middle">
        <div class="container-fluid">
            <div class="row">
                <?php if(!Yii::app()->user->isGuest):?>
                <div class="col-md-3 col-sm-3">
                    <div id="left-bar">
                        <ul id="left-menu">
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-user'></span>  Контакты", Yii::app()->createUrl('contact'));?>
                                <?php if(Yii::app()->user->role == User::ROLE_SECRETARY):?>
                                &nbsp; <?php echo CHtml::link('+', Yii::app()->createUrl('contact/create'), array('class'=>'btn btn-primary btn-xs')); ?>
                                <?php endif;?>
                            </li>
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-file'></span>  Договоры", Yii::app()->createUrl('agreement'));?></li>
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-calendar'></span>  Встречи", Yii::app()->createUrl('meetings'));?></li>
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-earphone'></span>  Звонки", Yii::app()->createUrl('calls'));?></li>
                            <?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-question-sign'></span>  Вопросы и ответы", Yii::app()->createUrl('question'));?></li>
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-log-in'></span>  Источники контактов", Yii::app()->createUrl('leadsource'));?></li>
                            <?php endif;?>
                        </ul>
                    </div>
                </div>
                <?php endif;?>
                <div class="col-md-9 col-sm-9">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </div>
    
    
</body>
</html>