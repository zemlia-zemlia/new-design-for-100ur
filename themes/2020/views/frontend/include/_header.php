<?php

use App\models\Order;
use App\models\User;

?>

    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!-- Переключение IE в последнию версию, на случай если в настройках пользователя стоит меньшая -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- Адаптирование страницы для мобильных устройств -->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <!-- Запрет распознования номера телефона -->
        <meta name="format-detection" content="telephone=no">
        <meta name="SKYPE_TOOLBAR" content ="SKYPE_TOOLBAR_PARSER_COMPATIBLE">

        <!-- Заголовок страницы -->
        <title><?= CHtml::encode($this->pageTitle); ?></title>
        <meta name="detectify-verification" content="9f97b18d45029185ceeda96efa4377e5"/>

        <!-- Данное значение часто используют(использовали) поисковые системы -->
        <meta name="description" content="">
        <meta name="keywords" content="">

        <!-- Традиционная иконка сайта, размер 16x16, прозрачность поддерживается. Рекомендуемый формат: .ico или .png -->
        <link rel="shortcut icon" href="img/favicon.ico">


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

        <!-- Подключение файлов стилей -->

        <?php
        Yii::app()->clientScript->registerCssFile("/lib/jquery-ui-1.12.1.custom/jquery-ui.min.css");
        Yii::app()->clientScript->registerCssFile("/lib/bootstrap/bootstrap-grid.min.css");
        Yii::app()->clientScript->registerCssFile("/lib/swiper/swiper.min.css");
        Yii::app()->clientScript->registerCssFile("/css/2020/style.css");
        Yii::app()->clientScript->registerCssFile("/fonts/fonts.css");
        Yii::app()->clientScript->registerCssFile("/css/2020/media.css");
        Yii::app()->clientScript->registerCssFile("https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap");
        Yii::app()->clientScript->registerScriptFile("/js/respond.min.js", CClientScript::POS_END);
//        Yii::app()->clientScript->registerScriptFile("/new-jquery/jquery-3.4.1.min.js");
        Yii::app()->clientScript->registerScriptFile("/js/2020/jquery-3.4.1.min.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile("/lib/jquery-ui-1.12.1.custom/jquery-ui.min.js", CClientScript::POS_END);
//        Yii::app()->clientScript->registerCssFile('/css/2017/jquery-ui.css');
        Yii::app()->clientScript->registerScriptFile('/lib/swiper/swiper.min.js', CClientScript::POS_END);

        Yii::app()->clientScript->registerCssFile("/css/robot_css.css");

//        Yii::app()->clientScript->registerScriptFile("/js/scripts.js");
        Yii::app()->clientScript->registerScriptFile("/lib/bootstrap/bootstrap.min.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile("/js/2020/main.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile("/js/scripts.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile("/js/2020/counters.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile("/js/2020/robot_widget.js", CClientScript::POS_END);
        Yii::app()->clientScript->registerScriptFile('/js/profile_notifier.js', CClientScript::POS_END);
        ?>

        <script charset="UTF-8"
                src="//cdn.sendpulse.com/28edd3380a1c17cf65b137fe96516659/js/push/5ed016b8521088d90d6a9ad8b03ca9e3_1.js"
                async></script>
        <?php if (!Yii::app()->user->isGuest): ?>
            <script type="text/javascript">
                window.addEventListener('load', function () {
                    oSpP.push("Name", "<?= CHtml::encode(Yii::app()->user->name); ?>");
                    oSpP.push("Email", "<?= Yii::app()->user->email; ?>");
                });
            </script>
        <?php endif; ?>

        <script>
          var robotWidgetQuestionUrl = "<?= Yii::app()->createUrl('/question/call/')?>";
        </script>


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
<!-- Header -->
<header class="header">
    <div class="header__mob">
        <div class="container">
            <div class="header__mob-wrap">
                <div class="nav-mob-wrap img">
                    <img src="/img/nav-open.png" alt="" class="nav-open">
                    <img src="/img/nav-close.png" alt="" class="nav-close">
                </div>
                <nav class="nav-mob">
                    <div class="nav-mob-close img">
                        <img src="/img/nav-mob-close.png" alt="">
                    </div>
                    <div class="nav-mob__title">Меню</div>
                    <ul class="nav-mob-list">

                        <?php if (Yii::app()->user->role == User::ROLE_CLIENT): ?>
                            <li class="main__nav-item">
                                <?= ($_SERVER['REQUEST_URI'] != '/question/my/') ? CHtml::link('Мои вопросы',
                                    Yii::app()->createUrl('/question/my/', ['class' => 'main__nav-link main__nav-link--question'])) :
                                    '<span class="active">Мои вопросы</span>'; ?></li>
                        <?php endif; ?>
                        <?php if (Yii::app()->user->role == User::ROLE_CLIENT): ?>

                            <?php if (Yii::app()->params['chat']['enabled']): ?>
                                <li> <?= CHtml::link('Чаты с юристами <strong class="red">(' . User::getChatsMessagesCnt() . ')</strong>', '/user/chats') ?></li>
                            <?php endif; ?>
                        <?php endif; ?>




                        <?php if (Yii::app()->user->role == User::ROLE_CLIENT || Yii::app()->user->isGuest): ?>

                            <li class="nav-mob-item"><?= ($_SERVER['REQUEST_URI'] != '/question/create/') ? CHtml::link('Задать вопрос юристу online',
                                    Yii::app()->createUrl('question/create') . '?utm_source=100yuristov&utm_medium=top-menu&utm_campaign=' .
                                    Yii::app()->controller->id, ['class' => 'nav-mob-link']) : ''; ?>
                            </li>
                            <li class="nav-mob-item"><?= ($_SERVER['REQUEST_URI'] != '/question/call/') ? CHtml::link('Консультация по телефону',
                                    Yii::app()->createUrl('/question/call/'), ['class' => 'nav-mob-link']) : '<span class="active">Консультация по телефону</span>'; ?>
                            </li>
                            <li class="nav-mob-item"><?= ($_SERVER['REQUEST_URI'] != '/q/') ? CHtml::link('Вопросы',
                                    Yii::app()->createUrl('/q/'), ['class' => 'nav-mob-link']) : '<span class="active">Найти юриста</span>'; ?>
                            </li>
                            <li class="nav-mob-item"><?= ($_SERVER['REQUEST_URI'] != '/yurist/russia/') ? CHtml::link('Найти юриста',
                                    Yii::app()->createUrl('/yurist/russia/'), ['class' => 'nav-mob-link']) : '<span class="active">Найти юриста</span>'; ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <?php
                    // выводим виджет с номером 8800
                    $this->widget('application.widgets.Hotline.HotlineWidget', [
                        'showAlways' => true,
                        'mobile' => true,
                        //'showPhone'     =>  false, // true - показать телефон, false - форму запроса города
                    ]);
                    ?>
                    <a href="<?= Yii::app()->createUrl('question/call') ?>" class="header__btn header__btn-mob">Заказать звонок</a>
                </nav>

                        <a class="logo-mob img" href="<?= Yii::app()->createUrl('/') ?>">
                            <img src="/img/logo.png" alt="100 Юристов и Адвокатов"
                                 title="Юридический портал"/>
                        </a>



                <a href="" class="search-btn img">
                    <img src="/img/search-btn.png" alt="">
                </a>
                <form action="" class="search-form-mob">
                    <div class="search-input">
                        <a href="" class="search-input__ico img">
                            <img src="/img/feedback-search.png" alt="">
                        </a>
                        <input type="search" name="" placeholder="Поиск...">
                    </div>
                </form>
                <a href="" class="login-btn img">
                    <img src="/img/login-btn.png" alt="">
                </a>
            </div>
        </div>
    </div>
    <div class="header__top">
        <div class="container">
            <div class="row no-gutters justify-content-between align-items-center">
                <div class="col-sm-12 col-md-auto">
                    <a href="<?= Yii::app()->createUrl('/') ?>" class="logo img">
                        <img src="/img/logo.png" alt="">
                    </a>
                </div>
                <div class="col-sm-auto col-md-auto header__question-wrap">
                    <div class="header__question">Задай вопрос юристу онлайн</div>
                </div>
                <div class="col-sm-auto col-md-auto">
                    <?php
                    // выводим виджет с номером 8800
                    $this->widget('application.widgets.Hotline.HotlineWidget', array(
                        'showAlways' => true,
                        //'showPhone'     =>  false, // true - показать телефон, false - форму запроса города
                    ));
                    ?>

                </div>
                <div class="col-sm-auto col-md-auto">
                    <a href="<?= Yii::app()->createUrl('question/call') ?>" class="header__btn">Заказать звонок</a>
                </div>
                <div class="col-sm-auto col-md-auto">
                    <?php if (Yii::app()->user->isGuest): ?>

                    <?php else: ?>

                            <ul class="hor-list-menu">
                                <?php if (Yii::app()->user->role == User::ROLE_PARTNER): ?>
                                    <?= CHtml::link('Перейти в панель вебмастера', Yii::app()->user->homeUrl); ?>
                                <?php elseif (Yii::app()->user->role == User::ROLE_BUYER): ?>
                                    <?= CHtml::link('Перейти в панель покупателя', Yii::app()->user->homeUrl); ?>
                                <?php else: ?>

                                    <li><?= CHtml::link((Yii::app()->user->lastName != '') ? Yii::app()->user->shortName : CHtml::encode(Yii::app()->user->name), Yii::app()->createUrl((Yii::app()->user->role == User::ROLE_BUYER) ? '/buyer' : '/user')); ?></li>
                                    <li>
                                        <?php
                                        $balance = Yii::app()->user->balance;
                                        ?>
                                        <small>
                                            Баланс: <?= CHtml::link(MoneyFormat::rubles($balance), Yii::app()->createUrl('transaction/index')); ?>
                                            руб.
                                        </small>
                                    </li>


                                    <li><?= CHtml::link('<span class="glyphicon glyphicon-log-out"></span>', Yii::app()->createUrl('site/logout'), array()); ?></li>
                                <?php endif; ?>
                            </ul>


                    <?php endif; ?>
                    <?php if (!stristr($_SERVER['REQUEST_URI'], '/question/create/')): ?>
                    <?php if (Yii::app()->user->isGuest): ?>

                            <a href="<?= Yii::app()->createUrl('/site/login/') ?>" class="header__account ">
							<span class="header__account-img img">
								<img src="/img/header-account.png" alt="">
							</span>
                                <span class="header__account-title <?= ($_SERVER['REQUEST_URI'] != '/site/login/') ? 'active' : '' ?>">
                                 Вход/Регистрация
                                </span>
                            </a>

                    <?php endif; ?>
                    <?php endif; ?>


                </div>
            </div>
        </div>
    </div>
    <div class="header__bottom">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-8">
                    <nav class="main__nav">
                        <ul class="main__nav-list">
                            <?php if (Yii::app()->user->role == User::ROLE_CLIENT): ?>
                                <li class="main__nav-item">
                                    <?= ($_SERVER['REQUEST_URI'] != '/question/my/') ? CHtml::link('Мои вопросы',
                                        Yii::app()->createUrl('/question/my/', ['class' => 'main__nav-link main__nav-link--question'])) :
                                        '<span class="active">Мои вопросы</span>'; ?></li>
                            <?php endif; ?>
                            <?php if (Yii::app()->user->role == User::ROLE_CLIENT): ?>

                                <?php if (Yii::app()->params['chat']['enabled']): ?>
                                    <li> <?= CHtml::link('Чаты с юристами <strong class="red">(' . User::getChatsMessagesCnt() . ')</strong>', '/user/chats') ?></li>
                                <?php endif; ?>
                            <?php endif; ?>




                            <?php if (Yii::app()->user->role == User::ROLE_CLIENT || Yii::app()->user->isGuest): ?>

                                <li class="main__nav-item"><?= ($_SERVER['REQUEST_URI'] != '/question/create/') ?
                                        CHtml::link('Задать  вопрос юристу online', Yii::app()->createUrl('question/create') .
                                            '?utm_source=100yuristov&utm_medium=top-menu&utm_campaign=' . Yii::app()->controller->id,
                                            ['class' => 'main__nav-link main__nav-link--question']) : ''; ?></li>


                                <li class="main__nav-item"><?= ($_SERVER['REQUEST_URI'] != '/question/call/') ?
                                        CHtml::link('Консультация по телефону', Yii::app()->createUrl('/question/call/'), ['class' => 'main__nav-item']) :
                                        '<span class="active">Консультация по телефону</span>'; ?></li>

                                <li class="main__nav-item"><?= ($_SERVER['REQUEST_URI'] != '/q/') ?
                                        CHtml::link('Вопросы', Yii::app()->createUrl('/q/'), ['class' => 'main__nav-item']) :
                                        '<span class="active">Вопросы</span>'; ?></li>
                                <li class="main__nav-item"><?= ($_SERVER['REQUEST_URI'] != '/yurist/russia/') ?
                                        CHtml::link('Найти юриста', Yii::app()->createUrl('/yurist/russia/'), ['class' => 'main__nav-item']) :
                                        '<span class="active">Найти юриста</span>'; ?></li>
                            <?php endif; ?>


                        </ul>
                    </nav>
                </div>
                <div class="col-lg-4">
                    <form action="" class="search-form">
                        <div class="search-input">
                            <a href="" class="search-input__ico img">
                                <img src="/img/feedback-search.png" alt="">
                            </a>
                            <input type="search" name="" placeholder="Поиск...">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>







        <div class="row">
            <div class="col-sm-12">
                <ul class="hor-list-menu">


                    <?php if (Yii::app()->user->role == User::ROLE_JURIST): ?>

                        <li><?= CHtml::link('Поиск вопросов', Yii::app()->createUrl('question/search')); ?></li>

                        <li><?= CHtml::link('Обновления для меня ' . '<strong class="red">(' . Yii::app()->user->newEventsCount . ')</strong>', Yii::app()->createUrl('user/feed')); ?>

                        </li>

                        <li><?= CHtml::link('Заказы документов ' . '<strong class="red">(' . Order::calculateNewOrders() . ')</strong>', Yii::app()->createUrl('order/index')); ?></li>
                        <?php if (Yii::app()->params['chat']['enabled']):?>
                            <li> <?= CHtml::link('Чаты с клиентами <strong class="red">(' . User::getChatsMessagesCnt() . ')</strong>', '/user/chats') ?></li>
                        <?php endif;?>
                        <li>

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

