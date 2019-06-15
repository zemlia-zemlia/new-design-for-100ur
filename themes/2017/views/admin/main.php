<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv="content-language" content="ru"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php
    Yii::app()->clientScript->registerCssFile("/bootstrap/css/bootstrap.min.css");
    Yii::app()->clientScript->registerCssFile('/js/jquery-ui/jquery-ui.css');
    Yii::app()->clientScript->registerCssFile("/css/2017/admin.css");

    Yii::app()->clientScript->registerScriptFile("/js/respond.min.js");
    Yii::app()->clientScript->registerScriptFile("jquery.js");
    Yii::app()->clientScript->registerScriptFile("/js/jquery-ui/jquery-ui.min.js");
    Yii::app()->clientScript->registerScriptFile("/bootstrap/js/bootstrap.min.js", CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile("/js/admin/scripts.js", CClientScript::POS_END);
    Yii::app()->ClientScript->registerScriptFile('/js/jquery.maskedinput.min.js', CClientScript::POS_END);
    ?>

</head>

<body>
<div id="main-wrapper">
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="/admin/">Панель управления проектом</a>

            </div>


            <p class="navbar-text navbar-right">
                <?php echo CHtml::link('Добавить лид', Yii::app()->createUrl('/admin/lead/create/'), array('class' => 'btn btn-info btn-xs')); ?></li>
                &nbsp;&nbsp;
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
                &nbsp;
                <span class="glyphicon glyphicon-user"></span> <?php echo CHtml::link(CHtml::encode(Yii::app()->user->name) . " " . CHtml::encode(Yii::app()->user->name2) . " " . CHtml::encode(Yii::app()->user->lastName), Yii::app()->createUrl('user')); ?>
                <?php echo Yii::app()->user->roleName; ?> &nbsp;
                <span class="glyphicon glyphicon-log-out"></span> <?php echo CHtml::link('Выход', Yii::app()->createUrl('site/logout')); ?>
            </p>

        </div>
    </nav>


    <div id="middle">
        <div class="container-fluid">
            <div class="row">
                <?php if (!Yii::app()->user->isGuest): ?>
                    <div class="col-md-3 col-sm-3">


                        <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->role == User::ROLE_SECRETARY): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <a role="button" data-toggle="collapse" href="#admin-collapse" aria-expanded="true"
                                       aria-controls="collapseExample">
                                        <h4>Админ панель</h4>
                                    </a>
                                </div>
                                <div class="collapse in" id="admin-collapse">
                                    <div class="panel-body">
                                        <ul id="left-menu">

                                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  Все лиды", Yii::app()->createUrl('/admin/lead/index')); ?>
                                                <ul>
                                                    <li><?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  На модерации", Yii::app()->createUrl('/admin/lead/index', array('status' => Lead::LEAD_STATUS_PREMODERATION))); ?>
                                                        <span class="label label-danger"><?php echo Lead::getStatusCounter(Lead::LEAD_STATUS_PREMODERATION, FALSE); ?></span>
                                                    </li>
                                                    <li>
                                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span>  На отбраковке", Yii::app()->createUrl('/admin/lead/index', array('status' => Lead::LEAD_STATUS_NABRAK))); ?>
                                                        <span class="label label-default"><?php echo Lead::getStatusCounter(Lead::LEAD_STATUS_NABRAK); ?></span>
                                                    </li>
                                                </ul>
                                            </li>

                                            <ul>
                                                <li>


                                                <li><?php echo CHtml::link("<span class='glyphicon glyphicon-briefcase'></span>  Заказы документов", Yii::app()->createUrl('/admin/order')); ?>
                                                    <span class="badge badge-default"><?php echo Order::calculateNewOrders(); ?></span>
                                                </li>
                                                <li><?php echo CHtml::link("<span class='glyphicon glyphicon-briefcase'></span>  Кампании", Yii::app()->createUrl('/admin/campaign')); ?></li>

                                                <?php if (!Yii::app()->user->role == User::ROLE_SECRETARY): ?>
                                                    <li><?php echo CHtml::link("<span class='glyphicon glyphicon-signal'></span>  Статистика", Yii::app()->createUrl('/admin/lead/stats')); ?></li>
                                                    <li><?php echo CHtml::link("<span class='glyphicon glyphicon-share-alt'></span>  Источники", Yii::app()->createUrl('/admin/leadsource')); ?></li>
                                                <?php endif; ?>
                                                <li><?php echo CHtml::link("<span class='glyphicon glyphicon-briefcase'></span>  Пользователи", Yii::app()->createUrl('/admin/user/index')); ?></li>
                                                <?php if (!Yii::app()->user->role == User::ROLE_SECRETARY): ?>
                                                    <li><?php echo CHtml::link("<span class='glyphicon glyphicon-share-alt'></span>  Касса", Yii::app()->createUrl('/admin/money')); ?></li>
                                                    <li><?php echo CHtml::link("<span class='glyphicon glyphicon-eye-close'></span>  Заявки на вывод средств ", Yii::app()->createUrl('/admin/partnerTransaction')); ?>
                                                        <span class="badge badge-default"><?php echo PartnerTransaction::getNewRequestsCount(); ?></span>
                                                    </li>
                                                <?php endif; ?>
                                                <li><?php echo CHtml::link("<span class='glyphicon glyphicon-eye-close'></span>  Запросы на смену статуса ", Yii::app()->createUrl('/admin/userStatusRequest')); ?>
                                                    <span class="badge badge-default"><?php echo UserStatusRequest::getNewRequestsCount(); ?></span>
                                                </li>


                                                </li></ul>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>


                        <?php if (Yii::app()->user->checkAccess(User::ROLE_EDITOR)): ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <a role="button" data-toggle="collapse" href="#content-collapse"
                                       aria-expanded="false" aria-controls="collapseExample">
                                        <h4>Управление контентом</h4>
                                    </a>
                                </div>
                                <div class="" id="content-collapse">
                                    <div class="panel-body">


                                        <ul id="left-menu">

                                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-question-sign'></span>  Вопросы", Yii::app()->createUrl('/admin/question')); ?>
                                                <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR) || Yii::app()->user->role == User::ROLE_SECRETARY): ?>
                                                    <ul>
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
                                                            <?php echo CHtml::link("<span class='glyphicon glyphicon-list'></span> " . 'Категории', Yii::app()->createUrl('/admin/questionCategory')); ?>
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
                                                <?php endif; ?>
                                            </li>

                                            <hr style="margin-top: 1px; margin-bottom: 1px;">

                                            <li><?php echo CHtml::link("<span class='glyphicon glyphicon-question-sign'></span>  Ответы юристов", Yii::app()->createUrl('/admin/answer')); ?>
                                                <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || Yii::app()->user->checkAccess(User::ROLE_EDITOR) || Yii::app()->user->role == User::ROLE_SECRETARY): ?>
                                                    <ul>
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
                                                <?php endif; ?>
                                            </li>

                                            <hr style="margin-top: 1px; margin-bottom: 1px;">

                                            <li>
                                                <ul id="left-menu">
                                                    <li>
                                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Новые комментарии', Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_ANSWER, 'status' => Comment::STATUS_NEW))); ?>
                                                        <span class="badge badge-default"><?php echo Comment::newCommentsCount(Comment::TYPE_ANSWER, 300); ?></span>
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
                                                <hr style="margin-top: 1px; margin-bottom: 1px;">

                                                <li>
                                                    <ul id="left-menu">
                                                        <li>
                                                            <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Новые отзывы', Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_USER, 'status' => Comment::STATUS_NEW))); ?>
                                                            <span class="badge badge-default"><?php echo Comment::newCommentsCount(Comment::TYPE_USER, 300); ?></span>
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
                                                <hr style="margin-top: 1px; margin-bottom: 1px;">
                                                <li><?php echo CHtml::link("<span class='glyphicon glyphicon-pencil'></span>  Новости", Yii::app()->createUrl('/admin/blog')); ?></li>
                                                <ul>
                                                    <li>
                                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-filter'></span> " . 'Новые комментарии к новостям', Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_POST, 'status' => Comment::STATUS_NEW))); ?>
                                                        <span class="badge badge-default"><?php echo Comment::newCommentsCount(Comment::TYPE_POST, 300); ?></span>
                                                    </li>
                                                    <li>
                                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-ok'></span> " . 'Одобренные комментарии', Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_POST, 'status' => Comment::STATUS_CHECKED))); ?>
                                                    </li>
                                                    <li>
                                                        <?php echo CHtml::link("<span class='glyphicon glyphicon-fire'></span> " . 'Спам комментарии', Yii::app()->createUrl('/admin/comment/index', array('type' => Comment::TYPE_POST, 'status' => Comment::STATUS_SPAM))); ?>
                                                    </li>
                                                </ul>

                                            <?php endif; ?>

                                            <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                                                <li><?php echo CHtml::link("<span class='glyphicon glyphicon-globe'></span>  Регионы", Yii::app()->createUrl('/admin/region')); ?></li>
                                                <li><?php echo CHtml::link("<span class='glyphicon glyphicon-envelope'></span>  Рассылки", Yii::app()->createUrl('/admin/mail/create')); ?></li>
                                            <?php endif; ?>


                                        </ul>

                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>
                <div class="col-md-9 col-sm-9">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </div>

</div> <!-- #main-wrapper-->

<?php if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1'): ?>

<?php endif; ?>

</body>
</html>