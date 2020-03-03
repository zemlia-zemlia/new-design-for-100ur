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
                <small><i class="fa fa-circle text-success"></i> <?php echo Yii::app()->user->roleName; ?></small>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="header">Личный кабинет</li>

        <?php if (Yii::app()->user->checkAccess(User::ROLE_BUYER)) : ?>

            <li><a href="<?= Yii::app()->createUrl('/buyer/'); ?>"><i class="fa fa-circle-o"></i> Главная</a></li>
            <li><a href="<?= Yii::app()->createUrl('/buyer/myLeads/'); ?>"><i class="fa fa-bars" aria-hidden="true"></i> Мои лиды</a></li>
            <li><a href="<?= Yii::app()->createUrl('/buyer/buyer/campaigns/'); ?>"><i class="fa fa-bars" aria-hidden="true"></i> Мои кампании</a></li>
            <li><a href="<?= Yii::app()->createUrl('/buyer/buyer/transactions/'); ?>"><i class="fa fa-money" aria-hidden="true"></i> Финансы</a></li>
            <li><a href="<?= Yii::app()->createUrl('/buyer/buyer/api/'); ?>"><i class="fa fa-wrench" aria-hidden="true"></i> Работа с API</a></li>
            <li><a href="<?= Yii::app()->createUrl('/buyer/buyer/faq/'); ?>"><i class="fa fa-question" aria-hidden="true"></i> FAQ</a></li>
            <li><a href="<?= Yii::app()->createUrl('/buyer/buyer/help/'); ?>"><i class="fa fa-life-ring" aria-hidden="true"></i> Техподдержка</a></li>
            <li><a href="https://www.yurcrm.ru/" target="_blank" rel="nofollow"><i class="fa fa-circle-o"></i> CRM для юристов  <i class="fa fa-external-link" aria-hidden="true"></i>
                </a></li>

        <?php endif; ?>

        <?php if (Yii::app()->user->checkAccess(User::ROLE_PARTNER)) : ?>

            <li><a href="<?= Yii::app()->createUrl('/webmaster/'); ?>"><i class="fa fa-circle-o"></i> Главная</a></li>
            <li><a href="<?= Yii::app()->createUrl('/webmaster/lead/create/'); ?>"><i class="fa fa-plus" aria-hidden="true"></i>Добавить новый лид</a></li>
            <li><a href="<?= Yii::app()->createUrl('/webmaster/lead/'); ?>"><i class="fa fa-bars" aria-hidden="true"></i>Все мои лиды</a></li>
            <li><a href="<?= Yii::app()->createUrl('/webmaster/source/'); ?>/"><i class="fa fa-cloud-download" aria-hidden="true"></i> Мои источники</a></li>
            <li><a href="<?= Yii::app()->createUrl('/webmaster/lead/prices/'); ?>"><i class="fa fa-money" aria-hidden="true"></i> Регионы и цены</a></li>
            <li><a href="<?= Yii::app()->createUrl('/webmaster/question/'); ?>"><i class="fa fa-comment" aria-hidden="true"></i>Привлеченные вопросы</a></li>
            <li><a href="<?= Yii::app()->createUrl('/webmaster/api/'); ?>"><i class="fa fa-wrench" aria-hidden="true"></i> Работа с API</a></li>
            <li><a href="<?= Yii::app()->createUrl('/webmaster/faq/'); ?>"><i class="fa fa-circle-o"></i> FAQ</a></li>
            <li><a href="<?= Yii::app()->createUrl('/webmaster/transaction/'); ?>"><i class="fa fa-money" aria-hidden="true"></i> Финансы</a></li>

        <?php endif; ?>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>