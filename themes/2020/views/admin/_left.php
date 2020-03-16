<?php

use App\models\Answer;
use App\models\Comment;
use App\models\Lead;
use App\models\Order;
use App\models\PartnerTransaction;
use App\models\Question;
use App\models\TransactionCampaign;
use App\models\User;
use App\models\UserStatusRequest;

?>
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

        <ul class="sidebar-menu">
            <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->role == User::ROLE_SECRETARY): ?>
                <li class="header">Админ панель</li>
                <li class="active treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Лиды</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="active"><a href="<?= Yii::app()->createUrl('/admin/lead/index') ?>"><i
                                        class="fa fa-circle-o"></i> Все лиды</a></li>
                        <li class="active"><a
                                    href="<?= Yii::app()->createUrl('/admin/lead/index', array('status' => Lead::LEAD_STATUS_PREMODERATION)) ?>"><i
                                        class="fa fa-circle-o"></i> На модерации
                                <small class="label pull-right bg-red"><?php echo Lead::getStatusCounter(Lead::LEAD_STATUS_PREMODERATION, FALSE); ?></small></a>
                        </li>
                        <li class="active"><a
                                    href="<?= Yii::app()->createUrl('/admin/lead/index', array('status' => Lead::LEAD_STATUS_NABRAK)) ?>"><i
                                        class="fa fa-circle-o"></i> На отбраковке
                                <small class="label pull-right bg-red"><?php echo Lead::getStatusCounter(Lead::LEAD_STATUS_NABRAK) ?></small></a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="<?= Yii::app()->createUrl('/admin/order') ?>">
                        <i class="fa fa-file-word-o" aria-hidden="true"></i><span>Заказы документов</span> <small
                                class="label pull-right bg-green"><?php echo Order::calculateNewOrders(); ?></small>
                    </a>
                </li>
                <li>
                    <a href="<?= Yii::app()->createUrl('/admin/campaign') ?>">
                        <i class="fa fa-th"></i> <span>Кампании</span>
                    </a>
                </li>


                <?php if (!Yii::app()->user->role == User::ROLE_SECRETARY): ?>


                    <li>
                        <a href="<?= Yii::app()->createUrl('/admin/lead/stats') ?>">
                            <i class="fa fa-area-chart" aria-hidden="true"></i> <span>Статистика</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::app()->createUrl('/admin/leadsource') ?>">
                            <i class="fa fa-th"></i> <span>Источники</span>
                        </a>
                    </li>

                <?php endif; ?>


                <li>
                    <a href="<?= Yii::app()->createUrl('/admin/user/index') ?>">
                        <i class="fa fa-user" aria-hidden="true"></i> <span>Пользователи</span>
                    </a>
                </li>

                <?php if (!Yii::app()->user->role == User::ROLE_SECRETARY): ?>

                    <li>
                        <a href="<?= Yii::app()->createUrl('/admin/money') ?>">
                            <i class="fa fa-money" aria-hidden="true"></i> <span>Касса</span>
                        </a>
                    </li>


                    <li>
                        <a href="#">
                            <i class="fa fa-pie-chart"></i>
                            <span>Вывод средств</span><span class="pull-right-container">
                                <small class="label pull-right bg-yellow"><?php echo PartnerTransaction::getNewRequestsCount(); ?></small>
                                <small class="label pull-right bg-green"><?php echo TransactionCampaign::getNewRequestsCount(); ?></small>
                            </span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="<?= Yii::app()->createUrl('/admin/partnerTransaction') ?>">
                                    <i class="fa fa-th"></i> <span>Вебмастера</span> <small
                                            class="label pull-right bg-yellow"><?php echo PartnerTransaction::getNewRequestsCount(); ?></small>
                                </a>
                            </li>
                            <li>
                                <a href="<?= Yii::app()->createUrl('/admin/campaignTransaction') ?>">
                                    <i class="fa fa-th"></i> <span>Юристы</span> <small
                                            class="label pull-right bg-green"><?php echo TransactionCampaign::getNewRequestsCount(); ?></small>
                                </a>
                            </li>
                        </ul>
                    </li>


                <?php endif; ?>

                <li>
                    <a href="<?= Yii::app()->createUrl('/admin/userStatusRequest') ?>">
                        <i class="fa fa-th"></i> <span>Смена статуса</span> <small
                                class="label pull-right bg-green"><?php echo UserStatusRequest::getNewRequestsCount(); ?></small>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (Yii::app()->user->checkAccess(User::ROLE_EDITOR)): ?>
                <li class="header">Управление контентом</li>

                <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR) || Yii::app()->user->role == User::ROLE_SECRETARY): ?>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-pie-chart"></i>
                            <span>Вопросы</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-list'></span> " . 'Все вопросы', Yii::app()->createUrl('/admin/question')); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-list'></span> " . 'VIP', Yii::app()->createUrl('/admin/question/vip')); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-adjust'></span> " . 'Недозаполненные', Yii::app()->createUrl('/admin/question/index', array('status' => Question::STATUS_PRESAVE))); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Email не указан', Yii::app()->createUrl('/admin/question/index', array('status' => Question::STATUS_NEW))); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-envelope'></span> " . 'Email не подтвержден', Yii::app()->createUrl('/admin/question/index', array('status' => Question::STATUS_NEW, 'email_unconfirmed' => 1))); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-check'></span> " . 'Ждет публикации', Yii::app()->createUrl('/admin/question/index', array('status' => Question::STATUS_MODERATED))); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Предв. опубликован', Yii::app()->createUrl('/admin/question/index', array('status' => Question::STATUS_CHECK))); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-ok'></span> " . 'Опубликованы', Yii::app()->createUrl('/admin/question/index', array('status' => Question::STATUS_PUBLISHED))); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-fire'></span> " . 'Спам', Yii::app()->createUrl('/admin/question/index', array('status' => Question::STATUS_SPAM))); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-chevron-right'></span> " . 'Вопросы без категории', Yii::app()->createUrl('/admin/question/nocat')); ?>
                            </li>


                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-list'></span> " . 'Направления', Yii::app()->createUrl('/admin/questionCategory/directions')); ?>
                            </li>

                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-chevron-right'></span> " . 'Редактирование', Yii::app()->createUrl('/admin/question/setTitle')); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-th-large'></span> " . 'Дубликаты', Yii::app()->createUrl('/admin/question/duplicates')); ?>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <?php echo CHtml::link("<span class='glyphicon glyphicon-list'></span> " . 'Категории справ. мат-ов', Yii::app()->createUrl('/admin/questionCategory')); ?>
                    </li>

                <?php endif; ?>

                <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR) || Yii::app()->user->role == User::ROLE_SECRETARY): ?>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-laptop"></i>
                            <span>Ответы юристов</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Все ответы', Yii::app()->createUrl('/admin/answer')); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Предв. опубликованные', Yii::app()->createUrl('/admin/answer/index', array('status' => Answer::STATUS_NEW))); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-ok'></span> " . 'Опубликованные', Yii::app()->createUrl('/admin/answer/index', array('status' => Answer::STATUS_PUBLISHED))); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-fire'></span> " . 'Спам', Yii::app()->createUrl('/admin/answer/index', array('status' => Answer::STATUS_SPAM))); ?>
                            </li>
                        </ul>
                    </li>


                <?php endif; ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-edit"></i> <span>Комментарии</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>


                            <a href="<?= Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_ANSWER, 'status' => Comment::STATUS_NEW)) ?>">
                                <i class="fa fa-th"></i> <span>Новые комментарии</span> <small
                                        class="label pull-right bg-green">
                                    <?php echo Comment::newCommentsCount(Comment::TYPE_ANSWER, 300); ?></small>
                            </a>

                        </li>
                        <li>
                            <?php echo CHtml::link("<span class='glyphicon glyphicon-ok'></span> " . 'Одобренные комментарии', Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_ANSWER, 'status' => Comment::STATUS_CHECKED))); ?>
                        </li>
                        <li>
                            <?php echo CHtml::link("<span class='glyphicon glyphicon-fire'></span> " . 'Спам комментарии', Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_ANSWER, 'status' => Comment::STATUS_SPAM))); ?>
                        </li>
                    </ul>
                </li>
                <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>

                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-table"></i> <span>Отзывы</span> <span><small
                                        class="label pull-right bg-green"><?php echo Comment::newCommentsCount(Comment::TYPE_USER, 300); ?></small></span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="<?= Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_USER, 'status' => Comment::STATUS_NEW)) ?>"><i
                                            class="fa fa-circle-o"></i> Новые отзывы
                                    <small class="label pull-right bg-green">
                                        <?php echo Comment::newCommentsCount(Comment::TYPE_USER, 300); ?></small> </a>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-ok'></span> " . 'Одобренные отзывы', Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_USER, 'status' => Comment::STATUS_CHECKED))); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-fire'></span> " . 'Спам отзывы', Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_USER, 'status' => Comment::STATUS_SPAM))); ?>
                            </li>
                        </ul>
                    </li>

                <?php endif; ?>
                <?php if (Yii::app()->user->checkAccess(User::ROLE_EDITOR)): ?>


                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-newspaper-o" aria-hidden="true"></i> <span>Новости</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-pencil'></span>  Все новости", Yii::app()->createUrl('/admin/blog')); ?></li>
                            <li>
                                <a href="<?= Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_POST, 'status' => Comment::STATUS_NEW)) ?>">
                                    <i class="fa fa-comments" aria-hidden="true"></i> Новые комментарии
                                    <small class="label pull-right bg-green">
                                        <?php echo Comment::newCommentsCount(Comment::TYPE_POST, 300); ?></small> </a>
                            </li>
                            <li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-ok'></span> " . 'Одобренные комментарии', Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_POST, 'status' => Comment::STATUS_CHECKED))); ?>
                            </li>
                            <li>
                                <?php echo CHtml::link("<span class='glyphicon glyphicon-fire'></span> " . 'Спам комментарии', Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_POST, 'status' => Comment::STATUS_SPAM))); ?>
                            </li>
                        </ul>
                    </li>


                <?php endif; ?>

                <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                    <li>
                        <a href="<?= Yii::app()->createUrl('/admin/region') ?>">
                            <i class="fa fa-th"></i> <span>Регионы</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::app()->createUrl('/admin/mail/create') ?>">
                            <i class="fa fa-at" aria-hidden="true"></i> <span>Рассылки</span>
                        </a>
                    </li>


                <?php endif; ?>
                <li>
                    <a href="<?= Yii::app()->createUrl('/admin/docs') ?>">
                        <i class="fa fa-th"></i> <span>Файлы</span>
                    </a>
                </li>

            <?php endif; ?>


        </ul>
    </section>
    <!-- /.sidebar -->
</aside>