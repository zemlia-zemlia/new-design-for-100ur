<?php
/* @var $this UserController */

use DateHelper as DateHelperAlias;

/* @var $model User */
$this->pageTitle = 'Профиль пользователя ' . CHtml::encode($model->name) . '. ' . Yii::app()->name;

Yii::app()->clientScript->registerScriptFile('/js/admin/user.js');

$this->breadcrumbs = [
    'Пользователи' => ['index'],
    CHtml::encode($model->name . ' ' . $model->lastName),
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/admin/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>

<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 1px;
    }
</style>
<?php if ($model->avatar): ?>
    <?php echo CHtml::image($model->getAvatarUrl('thumb'), '', ['class' => 'img-responsive']); ?>

    <?php if ($model->id == Yii::app()->user->id || Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
        <?php echo CHtml::link('Удалить аватар', Yii::app()->createUrl('/admin/user/removeAvatar', ['id' => $model->id])); ?>
    <?php endif; ?>
<?php endif; ?>

<h1 class="vert-margin30">
    <?php echo CHtml::encode($model->name) . ' ' . CHtml::encode($model->name2) . ' ' . CHtml::encode($model->lastName); ?>
    <?php if (User::ROLE_BUYER == $model->role): ?>
        <?php echo CHtml::link('Добавить кампанию', Yii::app()->createUrl('admin/campaign/create', ['buyerId' => $model->id]), ['class' => 'btn btn-primary']); ?>
    <?php endif; ?>
</h1>

<div class="row">
    <div class="col-md-6">
        <div class="box">
            <div class="box-body">
                <table class="table table-bordered">
                    <?php
                    // Показываем контактные данные сотрудников только секретарю и менеджерам
                    if (Yii::app()->user->checkAccess(User::ROLE_ROOT) || User::ROLE_SECRETARY == Yii::app()->user->role):
                        ?>
                        <tr>
                            <td><strong><?php echo $model->getAttributeLabel('email'); ?></strong></td>
                            <td><?php echo CHtml::encode($model->email); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo $model->getAttributeLabel('phone'); ?></strong></td>
                            <td><?php echo CHtml::encode($model->phone); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong><?php echo $model->getAttributeLabel('birthday'); ?></strong></td>
                        <td><?php echo DateHelperAlias::invertDate($model->birthday); ?></td>
                    </tr>
                    <?php if ($model->settings): ?>
                        <tr>
                            <td><strong>Город</strong></td>
                            <td><?php echo CHtml::encode($model->town->name . ' (' . $model->town->region->name . ')'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Год начала работы</strong></td>
                            <td><?php echo CHtml::encode($model->settings->startYear); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Описание</strong></td>
                            <td><?php echo CHtml::encode($model->settings->description); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Статус</strong></td>
                            <td>
                                <?php echo $model->settings->getStatusName(); ?>
                                <?php if ($model->settings->isVerified): ?>
                                    <span class="label label-success">подтвержден</span>
                                <?php else: ?>
                                    <span class="label label-warning">не подтвержден</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php if ($model->categories): ?>
                            <tr>
                                <td><strong>Специализации</strong></td>
                                <td>
                                    <?php foreach ($model->categories as $cat): ?>
                                        <span class="label label-default"><?php echo $cat->name; ?></span>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                    <tr>
                        <td>Баланс</td>
                        <td><?php echo (User::ROLE_PARTNER == $model->role) ? MoneyFormat::rubles($model->calculateWebmasterBalance()) : MoneyFormat::rubles($model->balance); ?>
                            руб.
                        </td>
                    </tr>
                    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT) && in_array($model->role, [User::ROLE_JURIST, User::ROLE_BUYER])): ?>
                        <td>Регистрация в YurCRM</td>
                        <td>
                            <?php if ($model->yurcrmSource > 0): ?>
                                Есть
                            <?php else: ?>
                                <div id="yurcrm-register-result">
                                    Нет
                                    <?php echo CHtml::ajaxLink('создать аккаунт', Yii::app()->createUrl('/admin/user/registerInCrm', ['id' => $model->id]), ['success' => 'onRegisterUserInCRM']); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                </table>

                <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                    <div class="vert-margin20">
                        <?php echo CHtml::link('Редактировать профиль', Yii::app()->createUrl('/admin/user/update', ['id' => $model->id]), ['class' => 'btn btn-primary']); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        <?php if (User::ROLE_JURIST == $model->role): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="box-title">График активности юриста</div>
                        </div>
                        <div class="box-body">
                            <?php if (User::ROLE_JURIST == $model->role): ?>
                                <?php $this->widget('application.widgets.UserActivity.UserActivityWidget', [
                                    'userId' => $model->id,
                                ]); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (User::ROLE_BUYER == $model->role): ?>
            <?php if ($transactionsDataProvider->totalItemCount): ?>
                <div class="box">
                    <div class="box-header">
                        <div class="box-title">Транзакции покупателя (юзера)</div>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Время</th>
                                <th>Кампания</th>
                                <th>Сумма</th>
                                <th>Описание</th>
                            </tr>

                            <?php
                            $this->widget('zii.widgets.CListView', [
                                'dataProvider' => $transactionsDataProvider,
                                'itemView' => 'application.views.transactionCampaign._view',
                                'emptyText' => 'Не найдено ни одной транзакции',
                                'summaryText' => 'Показаны транзакции с {start} до {end}, всего {count}',
                                'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
                            ]);
                            ?>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (User::ROLE_JURIST == $model->role): ?>
            <?php if ($transactionsDataProvider->totalItemCount): ?>
                <div class="box">
                    <div class="box-header">
                        <div class="box-title">Транзакции юриста</div>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Время</th>
                                <th>Кампания</th>
                                <th>Сумма</th>
                                <th>Описание</th>
                            </tr>

                            <?php
                            $this->widget('zii.widgets.CListView', [
                                'dataProvider' => $transactionsDataProvider,
                                'itemView' => 'application.views.transactionCampaign._view',
                                'emptyText' => 'Не найдено ни одной транзакции',
                                'summaryText' => 'Показаны транзакции с {start} до {end}, всего {count}',
                                'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
                            ]);
                            ?>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>


        <?php if (User::ROLE_BUYER == $model->role && $model->campaigns): ?>
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Кампании</div>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Кампания</th>
                            <th>Активность</th>
                            <th>Цена лида</th>
                        </tr>
                        <?php foreach ($model->campaigns as $campaign): ?>
                            <tr>
                                <td><?php echo $campaign->id . ' ' . CHtml::link(trim($campaign->town->name . ' ' . $campaign->region->name), Yii::app()->createUrl('/admin/campaign/view', ['id' => $campaign->id])); ?></td>
                                <td><?php echo $campaign->getActiveStatusName(); ?></td>
                                <td><?php echo MoneyFormat::rubles($campaign->price); ?> руб.</td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    </div>


    <div class="col-md-6">
        <?php if (!is_null($commentModel)): ?>
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Комментарии</div>
                </div>
                <div class="box-body">
                    <strong>Ваш комментарий:</strong>
                    <?php
                    $this->renderPartial('application.views.comment._form', [
                        'type' => Comment::TYPE_ADMIN,
                        'objectId' => $model->id,
                        'model' => $commentModel,
                        'hideRating' => true,
                        'parentId' => 0,
                    ]);
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($model->adminComments): ?>
            <div class="box box-info">
                <div class="box-body">
                    <?php foreach ($model->adminComments as $comment): ?>
                        <?php if (Comment::STATUS_SPAM != $comment->status): ?>
                            <div class="user-comment" style="margin-left:<?php echo($comment->level - 1) * 20; ?>px;">
                                <p>
                                    <strong><?php echo CHtml::encode($comment->author->name . ' ' . $comment->author->lastName); ?></strong>
                                    <span class="text-muted"><?php echo DateHelperAlias::niceDate($comment->dateTime, false, false); ?></span>
                                    <br/>
                                    <?php echo CHtml::encode($comment->text); ?>
                                </p>
                                <?php if (!is_null($commentModel)): ?>
                                    <div class="left-align">
                                        <a class="btn btn-xs btn-default" role="button" data-toggle="collapse"
                                           href="#collapse-comment-<?php echo $comment->id; ?>" aria-expanded="false">
                                            Ответить
                                        </a>
                                    </div>
                                    <div class="collapse child-comment-container"
                                         id="collapse-comment-<?php echo $comment->id; ?>">
                                        <strong>Ваш ответ:</strong>
                                        <?php
                                        $this->renderPartial('application.views.comment._form', [
                                            'type' => Comment::TYPE_ADMIN,
                                            'objectId' => $model->id,
                                            'model' => $commentModel,
                                            'hideRating' => true,
                                            'parentId' => $comment->id,
                                        ]);
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if (User::ROLE_CLIENT == $model->role): ?>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Вопросы пользователя</div>
                </div>
                <div class="box-body">
                    <?php if (sizeof($questions) > 0): ?>
                        <?php foreach ($questions as $question): ?>
                            <div class="row question-list-item  <?php if (1 == $question->payed): ?> vip-question<?php endif; ?>">
                                <div class="col-sm-10 col-xs-8">
                                    <p style="font-size:0.9em;">
                                        <?php echo (new DateTime($question->createDate))->format('d.m.Y'); ?>
                                        &nbsp;&nbsp;
                                        <?php if (1 == $question->payed) {
                                            echo "<span class='label label-warning'><abbr title='Вопрос с гарантией получения ответов'><span class='glyphicon glyphicon-ruble'></span></abbr></span>";
                                        }
                                        ?>
                                        <?php echo CHtml::link(StringHelper::mb_ucfirst($question->title, 'utf-8'), Yii::app()->createUrl('question/view', ['id' => $question->id])); ?>
                                    </p>
                                </div>

                                <div class="col-sm-2 col-xs-4 text-center">
                                    <small>
                                        <?php
                                        if (1 == $question->answersCount) {
                                            echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> Есть ответ</span>";
                                        } elseif ($question->answersCount > 1) {
                                            echo "<span class='text-success'> <span class='glyphicon glyphicon-ok'></span> " . $question->answersCount . ' ' . NumbersHelper::numForms($question->answersCount, 'ответ', 'ответа', 'ответов') . '</span>';
                                        } elseif (0 == $question->answersCount) {
                                            echo "<span class='text-muted'>Нет ответа</span>";
                                        }
                                        ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <?php if (User::ROLE_BUYER == $model->role): ?>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Статистика продаж лидов по дням</div>
                </div>
                <div class="box-body">
                    <div class="vert-margin30">
                        <?php
                        $this->renderPartial('application.modules.admin.views.lead._searchFormDates', [
                            'model' => $searchModel,
                            'action' => Yii::app()->createUrl('admin/user/view', ['id' => $model->id]),
                        ]);
                        ?>
                    </div>
                    <?php if (is_array($leadsStats) && is_array($leadsStats['dates'])): ?>
                        <table class="table table-bordered">
                            <tr>
                                <th>Дата</th>
                                <th class="text-right">Количество</th>
                                <th class="text-right">Сумма</th>
                            </tr>
                            <?php foreach ($leadsStats['dates'] as $date => $leadsByDate): ?>
                                <tr>
                                    <td><?php echo DateHelperAlias::niceDate($date, false, false); ?></td>
                                    <td class="text-right"><?php echo $leadsByDate['count']; ?></td>
                                    <td class="text-right"><?php echo MoneyFormat::rubles($leadsByDate['sum']); ?>
                                        руб.
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <th></th>
                                <th class="text-right"><?php echo $leadsStats['total']; ?></th>
                                <th class="text-right"><?php echo MoneyFormat::rubles($leadsStats['sum']); ?>руб.
                                </th>
                            </tr>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (User::ROLE_PARTNER == $model->role): ?>
        <div class="col-md-6 small">
        <?php if ($partnerTransactionsDataProvider): ?>
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Транзакции вебмастера</div>
                </div>
                <div class="box-body">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Дата</th>
                            <th>Сумма</th>
                            <th>Комментарий</th>
                        </tr>
                        </thead>
                        <?php
                        $this->widget('zii.widgets.CListView', [
                            'dataProvider' => $partnerTransactionsDataProvider,
                            'itemView' => '_partnerTransaction',
                            'emptyText' => 'Не найдено ни одной транзакции',
                            'summaryText' => 'Показаны транзакции с {start} до {end}, всего {count}',
                            'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
                        ]);
                        ?>
                    </table>
                </div>
            </div>
            </div>
        <?php endif; ?>


        <?php if (User::ROLE_PARTNER == $model->role): ?>
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header">
                        <div class="box-title">Статистика лидов по дням вебмастера</div>
                    </div>
                    <div class="box-body">
                        <?php $leadsStats = $model->getWebmasterLeadsStats(); ?>

                        <?php if (sizeof($leadsStats) > 0): ?>
                            <table class="table table-stripped">
                                <tr>
                                    <th>Регион</th>
                                    <th class="text-right">Лидов</th>
                                    <th class="text-right">Брак</th>
                                    <th class="text-right">% брака</th>
                                </tr>
                                <?php foreach ($leadsStats as $date => $statsByDate): ?>
                                    <tr>
                                        <th colspan="4"><?php echo DateHelperAlias::niceDate($date, false, true); ?></th>
                                    </tr>
                                    <?php foreach ($statsByDate as $regionName => $statsByRegion): ?>
                                        <tr>
                                            <td>
                                                &nbsp;&nbsp; <?php echo $regionName; ?>
                                            </td>
                                            <td class="text-right">
                                                <?php echo (int) $statsByRegion['total_leads']; ?>
                                            </td>
                                            <td class="text-right">
                                                <?php echo (int) $statsByRegion['brak_leads']; ?>
                                            </td>
                                            <td class="text-right">
                                                <?php
                                                echo ((int) $statsByRegion['total_leads'] > 0) ?
                                                    round((int) $statsByRegion['brak_leads'] / (int) $statsByRegion['total_leads'] * 100) . '%' :
                                                    '-';
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="col-md-6 small">
        <div class="box">
            <div class="box-header">
                <div class="box-title">Логирование дествий</div>
            </div>
            <div class="box-body">
                <?php
                // выводим виджет с последними записями лога
                $this->widget('application.widgets.LogReader.LogReaderWidget', [
                    'class' => 'User',
                    'subjectId' => $model->id,
                ]);
                ?>
            </div>
        </div>
    </div>

    <?php if (User::ROLE_PARTNER == $model->role): ?>
        <div class="col-md-6">
            <h2>Лиды вебмастера</h2>
            <?php
            $this->widget('zii.widgets.CListView', [
                'dataProvider' => $leadsDataProvider,
                'itemView' => 'application.modules.admin.views.lead._view',
                'emptyText' => 'Не найдено ни одного лида',
                'summaryText' => 'Показаны лиды с {start} до {end}, всего {count}',
                'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
            ]);
            ?>
        </div>
    <?php endif; ?>


</div>
