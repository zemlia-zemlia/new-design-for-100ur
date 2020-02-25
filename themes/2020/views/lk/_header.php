<header class="main-header">
    <!-- Logo -->
    <a href="/admin/" class="logo">
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
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <?php if (Yii::app()->user->checkAccess(User::ROLE_PARTNER)) :
                        $currentUser = User::model()->findByPk(Yii::app()->user->id);
                        $balance = $currentUser->calculateWebmasterBalance(30);
                        $hold = $currentUser->calculateWebmasterHold(30);
                        ?>
                        Доступно для вывода:<br /> <strong>
                        <?php if (($balance-$hold)< PartnerTransaction::MIN_WITHDRAW):?>
                            <small><span class="text-danger">Минимальная сумма для вывода - 1000&nbsp;руб.</span></small>
                        <?php else:?>
                            <?php echo MoneyFormat::rubles($balance - $hold);?> руб.</strong>
                        <?php endif;?>
                    <p>ХОЛД  <?php echo MoneyFormat::rubles($hold);?></p>

                    <?php endif; ?>
                    <?php if (Yii::app()->user->checkAccess(User::ROLE_BUYER)) : ?>
                        Ваш баланс: <?php echo MoneyFormat::rubles(Yii::app()->user->balance); ?> руб.
                        <?php endif; ?>
                </li>
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
                <!-- User Account: style can be found in dropdown.less -->
                <li class="user">

                    <?php echo CHtml::link(CHtml::encode(Yii::app()->user->shortName) , Yii::app()->createUrl('user')); ?>

                         <?php echo CHtml::link('<i class="glyphicon glyphicon-log-out"></i>', Yii::app()->createUrl('site/logout')); ?>


            </ul>
        </div>
    </nav>
</header>


<style>
    li.user a {
        display: inline-block;
    }
</style>