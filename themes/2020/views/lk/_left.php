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
        <ul class="sidebar-menu">
            <li class="header">Личный кабинет</li>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php if (Yii::app()->user->checkAccess(User::ROLE_BUYER)) : ?>


            <li><a href="<?= Yii::app()->createUrl('/buyer/'); ?>"><i class="fa fa-circle-o"></i> Главная</a></li>
            <!-- <li><a href="/lead/">Каталог лидов</a></li> -->
            <li><a href="<?= Yii::app()->createUrl('/buyer/transactions/'); ?>"><i class="fa fa-circle-o"></i> Баланс</a></li>
            <li><a href="<?= Yii::app()->createUrl('/buyer/api/'); ?>"><i class="fa fa-circle-o"></i> API</a></li>
            <li><a href="<?= Yii::app()->createUrl('/buyer/faq/'); ?>"><i class="fa fa-circle-o"></i> FAQ</a></li>
            <li><a href="http://www.yurcrm.ru/" target="_blank" rel="nofollow"><i class="fa fa-circle-o"></i> CRM для юристов</a></li>
            <li><a href="<?= Yii::app()->createUrl('/buyer/help/'); ?>"><i class="fa fa-circle-o"></i> Техподдержка</a></li>


        <?php endif; ?>
        <?php if (Yii::app()->user->checkAccess(User::ROLE_PARTNER)) : ?>

            <li><a href="<?= Yii::app()->createUrl('/webmaster/'); ?>"><i class="fa fa-circle-o"></i> Главная</a></li>
                <li><a href="<?= Yii::app()->createUrl('/webmaster/lead/'); ?>"><i class="fa fa-circle-o"></i> Мои лиды</a></li>
                <li><a href="<?= Yii::app()->createUrl('/webmaster/source'); ?>/"><i class="fa fa-circle-o"></i> Мои источники</a></li>
                <li><a href="<?= Yii::app()->createUrl('/webmaster/lead/prices/'); ?>"><i class="fa fa-circle-o"></i> Регионы и цены</a></li>
                <li><a href="<?= Yii::app()->createUrl('/webmaster/question/'); ?>"><i class="fa fa-circle-o"></i> Вопросы</a></li>
                <li><a href="<?= Yii::app()->createUrl('/webmaster/api/'); ?>"><i class="fa fa-circle-o"></i> API</a></li>
                <li><a href="<?= Yii::app()->createUrl('/webmaster/faq/'); ?>"><i class="fa fa-circle-o"></i> FAQ</a></li>
                <li><a href="<?= Yii::app()->createUrl('/webmaster/transaction/index/'); ?>"><i class="fa fa-circle-o"></i> Баланс</a></li>


        <?php endif; ?>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>