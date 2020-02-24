<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src=" <?= CHtml::encode(Yii::app()->user->avatarUrl); ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= CHtml::encode(Yii::app()->user->shortName); ?> </p>

                <a href="#"><i class="fa fa-circle text-success"></i><?php echo Yii::app()->user->roleName; ?></a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php if (Yii::app()->user->checkAccess(User::ROLE_BUYER)) : ?>
        <ul class="sidebar-menu">
            <li><a href="<?= Yii::app()->createUrl('/buyer/'); ?>">Главная</a></li>
            <!-- <li><a href="/lead/">Каталог лидов</a></li> -->
            <li><a href="/buyer/transactions/">Баланс</a></li>
            <li><a href="/buyer/api/">API</a></li>
            <li><a href="/buyer/faq/">FAQ</a></li>
            <li><a href="http://www.yurcrm.ru/" target="_blank" rel="nofollow">CRM для юристов</a></li>
            <li><a href="/buyer/help/">Техподдержка</a></li>

        </ul>
        <?php endif; ?>
        <?php if (Yii::app()->user->checkAccess(User::ROLE_PARTNER)) : ?>
        <ul class="sidebar-menu">
            <li><a href="/webmaster/">Главная</a></li>
                <li><a href="/webmaster/lead/">Мои лиды</a></li>
                <li><a href="/webmaster/source/">Мои источники</a></li>
                <li><a href="/webmaster/lead/prices/">Регионы и цены</a></li>
                <li><a href="/webmaster/question/">Вопросы</a></li>
                <li><a href="/webmaster/api/">API</a></li>
                <li><a href="/webmaster/faq/">FAQ</a></li>
                <li><a href="/webmaster/transaction/index/">Баланс</a></li>

        </ul>
        <?php endif; ?>
    </section>
    <!-- /.sidebar -->
</aside>