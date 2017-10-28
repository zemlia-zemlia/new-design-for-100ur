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
    Yii::app()->clientScript->registerScriptFile("/js/respond.min.js",CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("jquery.js");
    Yii::app()->clientScript->registerScriptFile("/bootstrap/js/bootstrap.min.js",CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("/js/jquery.maskedinput.min.js",CClientScript::POS_END);
    Yii::app()->clientScript->registerCssFile('/css/2017/jquery-ui.css');
    Yii::app()->clientScript->registerScriptFile('/js/jquery-ui.min.js',CClientScript::POS_END);
    
    Yii::app()->clientScript->registerScriptFile("/js/scripts.js");
    Yii::app()->clientScript->registerScriptFile("/js/jquery.placeholder.min.js",CClientScript::POS_END);

?>
<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
</head>  

<body>
    
    <div id="header">
        <div class="container">
            
            <div class="row">
                <div class="col-md-12 right-align">
                    <ul class="hor-list-menu">
                        <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/goryachaya_liniya/')?CHtml::link('Горячая линия', Yii::app()->createUrl('/site/goryachaya_liniya/')):'<span class="active">Горячая линия</span>';?></li>
                        <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/contacts/')?CHtml::link('Филиалы', Yii::app()->createUrl('/site/contacts')):'<span class="active">Филиалы</span>';?></li>
                        <li><?php echo ($_SERVER['REQUEST_URI'] != '/blog/')?CHtml::link('Блог', Yii::app()->createUrl('/blog')):'<span class="active">Блог</span>';?></li>
                        <?php if(Yii::app()->user->isGuest):?>
                            <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/login/')?CHtml::link('Вход', Yii::app()->createUrl('/site/login/')):'<span class="active">Вход</span>';?></li> 
                        <?php else:?>
                            <li>|</li>
                            <li><?php echo CHtml::link((Yii::app()->user->lastName != '') ? Yii::app()->user->shortName : CHtml::encode(Yii::app()->user->name), Yii::app()->createUrl((Yii::app()->user->role == User::ROLE_BUYER)? '/cabinet':'/user'));?></li>
                            <?php if(Yii::app()->user->role == User::ROLE_PARTNER):?>
                                <li><?php echo CHtml::link('Кабинет', Yii::app()->createUrl('/webmaster'), array('class'=>''));?></li>
                            <?php endif;?>
                                
                            <?php if(Yii::app()->user->role == User::ROLE_BUYER || Yii::app()->user->role == User::ROLE_PARTNER):?>
                                <li>
                                        <?php 
                                            // найдем баланс пользователя. если это не вебмастер:
                                            if(Yii::app()->user->role != User::ROLE_PARTNER) {
                                                $balance = Yii::app()->user->balance;
                                                $transactionPage = '/cabinet/transactions';
                                            } else {
                                                $currentUser = User::model()->findByPk(Yii::app()->user->id);
        
                                                // если это вебмастер, кешируем баланс, рассчитанный из транзакций вебмастера
                                                if($cachedBalance = Yii::app()->cache->get('webmaster_' . Yii::app()->user->id . '_balance')) {
                                                    $balance = $cachedBalance;
                                                } else {
                                                    $balance = $currentUser->calculateWebmasterBalance();
                                                    Yii::app()->cache->set('webmaster_' . Yii::app()->user->id . '_balance', $balance, 3600);
                                                }
                                                $transactionPage = '/webmaster/transaction/index';
                                            }
                                        ?>
                                        Баланс: <?php echo CHtml::link($balance, Yii::app()->createUrl($transactionPage));?> руб.
                                        <?php if(Yii::app()->user->campaignsModeratedCount > 0):?>
                                            <?php echo CHtml::link("<span class='glyphicon glyphicon-plus'></span>", Yii::app()->createUrl('cabinet/topup'), array('title' => 'Пополнить'));?>
                                        <?php endif;?>

                                </li>  
                            <?php endif;?>
                                
                            <li><?php echo CHtml::link('<span class="glyphicon glyphicon-log-out"></span>', Yii::app()->createUrl('site/logout'), array());?></li>
                        <?php endif;?>
                    </ul>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="logo-wrapper">
                        <?php if($_SERVER['REQUEST_URI'] != '/'):?>
                        <a href="/">
                            <img src="/pics/2017/100_yuristov_logo.svg" alt="100 Юристов и Адвокатов" title="Юридический портал" style="width:276px; height:75px;" />
                        </a>
                        <?php else:?>
                            <img src="/pics/2017/100_yuristov_logo.svg" alt="100 Юристов и Адвокатов" title="Юридический портал" style="width:276px; height:75px;" />
                        <?php endif;?>      
                    </div>			
                </div>
		
                <?php if(Yii::app()->user->isGuest):?>
                    <div class="col-md-4 col-sm-4">
                <?php else:?>
                    <div class="col-md-6 col-sm-6 center-align"></div>
                    <div class="col-md-3 col-sm-3 center-align">  
                <?php endif;?>
                
                    <?php if(Yii::app()->user->isGuest):?>
                        
                        <?php
                            // выводим виджет с номером 8800
                            $this->widget('application.widgets.Hotline.HotlineWidget', array(
                                'showAlways'    => true,
                                //'showPhone'     =>  false, // true - показать телефон, false - форму запроса города
                            ));
                        ?>
                    <?php else:?>                    
                        <?php
                            // выводим виджет с формой логина
//                            $this->widget('application.widgets.Login.LoginWidget', array(
//                            ));
                        ?>
                    <?php endif;?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <ul class="hor-list-menu">
                        <li class="hidden-xs">
                            <?php echo ($_SERVER['REQUEST_URI'] != '/cat/')?CHtml::link('Темы вопросов', Yii::app()->createUrl('/cat/'), array('class' => 'black-button')):'<span class="active">Темы вопросов</span>';?> 			
                        </li>
                        <li class="visible-xs-inline"><?php echo ($_SERVER['REQUEST_URI'] != '/yurist/')?CHtml::link('Каталог юристов', Yii::app()->createUrl('/yurist/')):'<span class="active">Каталог юристов</span>';?></li>
                        <li class="hidden-xs"><?php echo ($_SERVER['REQUEST_URI'] != '/question/call/')?CHtml::link('Заказать звонок', Yii::app()->createUrl('/question/call/')):'<span class="active">Заказать звонок</span>';?></li>
                        <li><?php echo (!stristr($_SERVER['REQUEST_URI'], '/question/docs/'))?CHtml::link("Заказать документы", Yii::app()->createUrl('question/docs'), array('class'=>'')):'<span class="active">Заказать документы</span>'; ?></li>
                        <li><?php echo (!stristr($_SERVER['REQUEST_URI'], '/question/services/'))?CHtml::link("Заказать услуги", Yii::app()->createUrl('question/services'), array('class'=>'')):'<span class="active">Заказать услуги</span>'; ?></li>    
                        <li><?php echo (!stristr($_SERVER['REQUEST_URI'], '/question/create/'))?CHtml::link('Задать бесплатный вопрос юристу', Yii::app()->createUrl('question/create') . '?utm_source=100yuristov&utm_medium=top-menu&utm_campaign='.Yii::app()->controller->id, array('class' => 'yellow-button arrow')):'';?></li>
                        <?php if(!stristr($_SERVER['REQUEST_URI'], '/question/create/')):?>
                        <li class="hidden-xs">Круглосуточно</li>
                        <?php endif;?>
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
        </div> <!-- .container -->
    </div> <!-- #header -->