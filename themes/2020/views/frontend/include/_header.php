<?php

use App\models\Order;
use App\models\User;

?>
<!doctype html>
<html lang="ru">
<head>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="detectify-verification" content="9f97b18d45029185ceeda96efa4377e5"/>

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600&subset=latin,cyrillic' rel='stylesheet'
          type='text/css'>

    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-MNNZGSK');</script>
    <!-- End Google Tag Manager -->

    <?php
    Yii::app()->clientScript->registerCssFile("/bootstrap/css/bootstrap.min.css");
    Yii::app()->clientScript->registerCssFile("/css/2017/style.css");
    Yii::app()->clientScript->registerScriptFile("/js/respond.min.js", CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("jquery.js");
    Yii::app()->clientScript->registerScriptFile("/bootstrap/js/bootstrap.min.js", CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("/js/jquery.maskedinput.min.js", CClientScript::POS_END);
    Yii::app()->clientScript->registerCssFile('/css/2017/jquery-ui.css');
    Yii::app()->clientScript->registerScriptFile('/js/jquery-ui.min.js', CClientScript::POS_END);

    Yii::app()->clientScript->registerCssFile("/css/robot_css.css");

    Yii::app()->clientScript->registerScriptFile("/js/scripts.js");
    Yii::app()->clientScript->registerScriptFile("/js/jquery.placeholder.min.js", CClientScript::POS_END);
    ?>
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <script charset="UTF-8"
            src="//cdn.sendpulse.com/28edd3380a1c17cf65b137fe96516659/js/push/5ed016b8521088d90d6a9ad8b03ca9e3_1.js"
            async></script>
    <?php if (!Yii::app()->user->isGuest): ?>
        <script type="text/javascript">
            window.addEventListener('load', function () {
                oSpP.push("Name", "<?php echo CHtml::encode(Yii::app()->user->name); ?>");
                oSpP.push("Email", "<?php echo Yii::app()->user->email; ?>");
            });
        </script>
    <?php endif; ?>
</head>

<body>

<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MNNZGSK"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->


<?php
// выводим виджет с подсказкой юристу
$this->widget('application.widgets.ProfileNotifier.ProfileNotifier', []);
?>
<div id="header">
    <div class="container">

        <div class="row">
            <div class="col-md-12 right-align">

            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-4">
                <div class="logo-wrapper">
                    <?php if ($_SERVER['REQUEST_URI'] != '/'): ?>
                        <a href="/">
                            <img src="/pics/2017/100_yuristov_logo.png" alt="100 Юристов и Адвокатов"
                                 title="Юридический портал"/>
                        </a>
                    <?php else: ?>
                        <img src="/pics/2017/100_yuristov_logo.png" alt="100 Юристов и Адвокатов"
                             title="Юридический портал"/>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (Yii::app()->user->isGuest): ?>
                <div class="col-md-4 col-sm-4"></div>
            <?php else: ?>
                <div class="col-md-8 col-sm-8 right-align">
                    <ul class="hor-list-menu">
                        <?php if (Yii::app()->user->role == User::ROLE_PARTNER): ?>
                            <?php echo CHtml::link('Перейти в панель вебмастера', Yii::app()->user->homeUrl); ?>
                        <?php elseif (Yii::app()->user->role == User::ROLE_BUYER): ?>
                            <?php echo CHtml::link('Перейти в панель покупателя', Yii::app()->user->homeUrl); ?>
                        <?php else: ?>

                            <li><?php echo CHtml::link((Yii::app()->user->lastName != '') ? Yii::app()->user->shortName : CHtml::encode(Yii::app()->user->name), Yii::app()->createUrl((Yii::app()->user->role == User::ROLE_BUYER) ? '/buyer' : '/user')); ?></li>
                            <li>
                                <?php
                                $balance = Yii::app()->user->balance;
                                ?>
                                <small>
                                    Баланс: <?php echo CHtml::link(MoneyFormat::rubles($balance), Yii::app()->createUrl('transaction/index')); ?>
                                    руб.
                                </small>
                            </li>


                            <li><?php echo CHtml::link('<span class="glyphicon glyphicon-log-out"></span>', Yii::app()->createUrl('site/logout'), array()); ?></li>
                        <?php endif; ?>
                    </ul>
                </div>

            <?php endif; ?>

            <?php if (Yii::app()->user->isGuest): ?>
                <div class="col-md-4 col-sm-4">
                    <?php
                    // выводим виджет с номером 8800
                    $this->widget('application.widgets.Hotline.HotlineWidget', array(
                        'showAlways' => true,
                        //'showPhone'     =>  false, // true - показать телефон, false - форму запроса города
                    ));
                    ?>
                </div>
            <?php endif; ?>

        </div>

        <div class="row">
            <div class="col-sm-12">
                <ul class="hor-list-menu">


                    <?php if (Yii::app()->user->role == User::ROLE_JURIST): ?>

                        <li><?php echo CHtml::link('Поиск вопросов', Yii::app()->createUrl('question/search')); ?></li>

                        <li><?php echo CHtml::link('Обновления для меня ' . '<strong class="red">(' . Yii::app()->user->newEventsCount . ')</strong>', Yii::app()->createUrl('user/feed')); ?>

                        </li>

                        <li><?php echo CHtml::link('Заказы документов ' . '<strong class="red">(' . Order::calculateNewOrders() . ')</strong>', Yii::app()->createUrl('order/index')); ?></li>
                        <?php if (Yii::app()->params['chat']['enabled']):?>
                            <li> <?= CHtml::link('Чаты с клиентами <strong class="red">(' . User::getChatsMessagesCnt() . ')</strong>', '/user/chats') ?></li>
                        <?php endif;?>
                        <li>

                    <?php elseif (Yii::app()->user->role == User::ROLE_CLIENT || Yii::app()->user->isGuest): ?>

                        <li><?php echo ($_SERVER['REQUEST_URI'] != '/question/create/') ? CHtml::link('Задать свой вопрос юристам online', Yii::app()->createUrl('question/create') . '?utm_source=100yuristov&utm_medium=top-menu&utm_campaign=' . Yii::app()->controller->id, array('class' => 'yellow-button arrow')) : ''; ?></li>


                        <li class="hidden-xs"><?php echo ($_SERVER['REQUEST_URI'] != '/question/call/') ? CHtml::link('Консультация по телефону', Yii::app()->createUrl('/question/call/')) : '<span class="active">Консультация по телефону</span>'; ?></li>

                        <li class=""><?php echo ($_SERVER['REQUEST_URI'] != '/yurist/russia/') ? CHtml::link('Юристы', Yii::app()->createUrl('/yurist/russia/')) : '<span class="active">Юристы</span>'; ?></li>

                        <?php if (Yii::app()->user->role == User::ROLE_CLIENT): ?>
                            <li class=""><?php echo ($_SERVER['REQUEST_URI'] != '/question/my/') ? CHtml::link('Мои вопросы', Yii::app()->createUrl('/question/my/')) : '<span class="active">Мои вопросы</span>'; ?></li>
                        <?php endif; ?>
                        <?php if (Yii::app()->user->role == User::ROLE_CLIENT): ?>

                            <?php if (Yii::app()->params['chat']['enabled']): ?>
                                <li> <?= CHtml::link('Чаты с юристами <strong class="red">(' . User::getChatsMessagesCnt() . ')</strong>', '/user/chats') ?></li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (!stristr($_SERVER['REQUEST_URI'], '/question/create/')): ?>

                            <?php if (Yii::app()->user->isGuest): ?>
                                <li><?php echo ($_SERVER['REQUEST_URI'] != '/site/login/') ? CHtml::link('Вход на сайт', Yii::app()->createUrl('/site/login/')) : '<span class="active">Вход на сайт</span>'; ?></li>
                            <?php endif; ?>

                        <?php endif; ?>


                    <?php endif; ?>

                    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                        <li>
                            <?php
                            echo CHtml::ajaxLink("Кеш", Yii::app()->createUrl('site/clearCache'), array(
                                'success' => 'function(data, textStatus, jqXHR)
                                                {
                                                    if(data==1) message = "Кэш очищен";
                                                    else message = "Не удалось очистить кэш";
                                                    alert(message);
                                                }'
                            ), array('title' => 'Очистить кеш страницы'));
                            ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div> <!-- .container -->
</div> <!-- #header -->
