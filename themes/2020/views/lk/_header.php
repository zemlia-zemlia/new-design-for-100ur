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
                    <div class="row center-block balance-block-byer-webmaster">
                        <?php if (Yii::app()->user->checkAccess(User::ROLE_PARTNER)) :
                            $currentUser = User::model()->findByPk(Yii::app()->user->id);
                            $balance = $currentUser->calculateWebmasterBalance(30);
                            $hold = $currentUser->calculateWebmasterHold(30);
                            ?>
                            <div class="col-md-6 block">
                            <p>Баланс:<?php if (($balance - $hold) < PartnerTransaction::MIN_WITHDRAW): ?> <i class="fa fa-rub" aria-hidden="true"></i>
                            </p>
                        <?php else: ?>
                            <?php echo MoneyFormat::rubles($balance - $hold); ?>
                            </div>
                        <?php endif; ?>
                            <div class="col-md-6 hidden-xs block">
                                <p> Холд: <br><?php echo MoneyFormat::rubles($hold); ?> <i class="fa fa-rub" aria-hidden="true"></i>
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if (Yii::app()->user->checkAccess(User::ROLE_BUYER)) : ?>
                        <div class="col-md-12 align-center block">
                            <p> Баланс: <br> <?php echo MoneyFormat::rubles(Yii::app()->user->balance); ?> <i class="fa fa-rub" aria-hidden="true"></i></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </li>

                <li class="user">
                    <?php echo CHtml::link('<i class="fa  fa-user" aria-hidden="true"></i>' . CHtml::encode(Yii::app()->user->shortName),  Yii::app()->createUrl('user')); ?>
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