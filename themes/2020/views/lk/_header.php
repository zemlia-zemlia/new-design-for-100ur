<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
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
                    <div class="row center-block balance-block-byer-webmaster hidden-xs">
                        <?php if (Yii::app()->user->checkAccess(User::ROLE_PARTNER)) :
                            $currentUser = User::model()->findByPk(Yii::app()->user->id);
                            $balance = $currentUser->calculateWebmasterBalance(30);
                            $hold = $currentUser->calculateWebmasterHold(30);
                            ?>
                            <div class="col-md-6 align-center">
                            Баланс:<?php if (($balance - $hold) < PartnerTransaction::MIN_WITHDRAW): ?>
                        <?php else: ?>
                            <?php echo MoneyFormat::rubles($balance - $hold); ?>
                            </div>
                        <?php endif; ?>
                            <div class="col-md-6">
                                Холд: <br><?php echo MoneyFormat::rubles($hold); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (Yii::app()->user->checkAccess(User::ROLE_BUYER)) : ?>
                            Ваш баланс: <br> <?php echo MoneyFormat::rubles(Yii::app()->user->balance); ?> руб.
                        <?php endif; ?>
                    </div>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li class="user">

                    <?php echo CHtml::link(CHtml::encode(Yii::app()->user->shortName), Yii::app()->createUrl('user')); ?>

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