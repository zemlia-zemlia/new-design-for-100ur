<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo Yii::app()->user->getHomeUrl(); ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>100</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>100</b> Юристов</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <?php
        $questionRepository = new QuestionRepository();
        $questionRepository->setCacheTime(600)->setLimit(10);
        $questionsCountNoCat = $questionRepository->countNoCat();
        $questionsCountForModerate = $questionRepository->countForModerate();


        ?>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <?php if (Yii::app()->user->role == User::ROLE_PARTNER) :
                    $currentUser = User::model()->findByPk(Yii::app()->user->id);
                    $balance = $currentUser->calculateWebmasterBalance(30);
                    $hold = $currentUser->calculateWebmasterHold(30);
                    ?>
                    <li>
                        <?php echo CHtml::link('<i class="fa fa-rub" aria-hidden="true"></i> ' . MoneyFormat::rubles($balance - $hold), Yii::app()->createUrl('/webmaster/transaction/')); ?>
                    </li>

                    <li class="hidden-xs">
                        <?php echo CHtml::link('Холд: ' . MoneyFormat::rubles($hold) . ' <i class="fa fa-rub" aria-hidden="true"></i>', Yii::app()->createUrl('/webmaster/transaction/')); ?>
                    </li>
                <?php endif; ?>

                <?php if (Yii::app()->user->role == User::ROLE_BUYER) : ?>
                    <li>
                        <a href="<?php echo Yii::app()->createUrl('buyer/buyer/transactions'); ?>">
                            <i class="fa fa-rub"
                               aria-hidden="true"></i> <?php echo MoneyFormat::rubles(Yii::app()->user->balance); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (Yii::app()->user->role ==User::ROLE_EDITOR): ?>
                    <li>
                        <a href="<?= Yii::app()->createUrl('/admin/question/setTitle/') ?>">Модерировать: <?= $questionsCountForModerate ?></a>
                    </li>

                    <li>
                        <a href="<?= Yii::app()->createUrl('/admin/question/nocat/') ?>">Без
                            категории: <?= $questionsCountNoCat ?></a>
                    </li>
                <?php endif; ?>

                <li class="dropdown notifications-menu">
                    <?php
                    echo CHtml::ajaxLink("<span class='glyphicon glyphicon-refresh'></span>", Yii::app()->createUrl('site/clearCache'), array(
                        'success' => 'function(data, textStatus, jqXHR)
                                {
                                    if(data==1) message = "Кэш очищен";
                                    else message = "Не удалось очистить кэш";
                                    alert(message);
                                }'
                    ), array('title' => 'Очистить кеш страницы'));
                    ?>
                </li>

                <li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i>

                    </a>
                </li>

                <li class="user">
                    <?php echo CHtml::link(CHtml::encode(Yii::app()->user->shortName), Yii::app()->createUrl('user')); ?>
                    <?php echo CHtml::link('<i class="glyphicon glyphicon-log-out"></i>', Yii::app()->createUrl('site/logout')); ?>
                </li>
            </ul>
        </div>
    </nav>
</header>


<style>
    li.user a {
        display: inline-block;
    }
</style>