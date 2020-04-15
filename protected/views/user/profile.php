<?php
/* @var $this UserController */

use App\helpers\DateHelper;
use App\helpers\StringHelper;
use App\models\User;
use App\models\YuristSettings;

/* @var $model User */
/**
 * Просмотр профиля юриста.
 */
// Построим цепочку хлебных крошек из ссылок на город и регион юриста

$town = $user->town;
if ($town) {
    $region = $town->region;
    $country = $town->country;
}

$this->breadcrumbs = [];

if ($country) {
    $this->breadcrumbs[$country->name] = Yii::app()->createUrl('region/country', ['countryAlias' => $country->alias]);
}
if ($region && $country) {
    $this->breadcrumbs[$region->name] = Yii::app()->createUrl('region/view', ['countryAlias' => $country->alias, 'regionAlias' => $region->alias]);
}
if ($town && $region && $country) {
    $this->breadcrumbs[$town->name] = Yii::app()->createUrl('town/alias', ['countryAlias' => $country->alias, 'regionAlias' => $region->alias, 'name' => $town->alias]);
}

$this->breadcrumbs[] = CHtml::encode($user->getNameOrCompany());

$title = CHtml::encode($user->getNameOrCompany());

if ($user->settings) {
    $title = $user->settings->getStatusName() . ' ' . $title;
}

$title .= ' город ' . $user->town->name;

$this->setPageTitle($title . '. ');

// формируем метаописание профиля
$pageDescription = '';
if ($user->settings) {
    // для юриста выведем его статус (юрист/адвокат)
    $pageDescription .= $user->settings->getStatusName() . ' ';
}
$pageDescription .= CHtml::encode($user->getNameOrCompany()) . '. ';
if ($user->town) {
    $pageDescription .= $user->town->name;
}
Yii::app()->clientScript->registerMetaTag($pageDescription, 'description');

if (Yii::app()->user->id != $user->id) {
    $this->widget('zii.widgets.CBreadcrumbs', [
        'homeLink' => CHtml::link('100 Юристов', '/'),
        'separator' => ' / ',
        'links' => $this->breadcrumbs,
    ]);
}
?>

<div class="vert-margin30" itemscope itemtype="http://schema.org/Person">
    <h1 class='vert-margin30'>
        <span itemprop="name">
            <?php
            echo CHtml::encode($user->getNameOrCompany());
            ?>
        </span>

        <?php if ($user->settings): ?>
            <span itemprop="jobTitle">
                <i><?php echo $user->settings->getStatusName(); ?></i>
            </span>
        <?php endif; ?>
    </h1>

    <div class='vert-margin20'>
        <div class="row">
            <div class="col-sm-4 center-align">
                <div class="row">
                    <div class="col-md-12">
                        <?php if ($user->id == Yii::app()->user->id): ?>
                            <?php if (User::ROLE_JURIST == Yii::app()->user->role): ?>
                                <?php if ($lastRequest && 0 == $lastRequest['isVerified']): ?>
                                    <div class='alert alert-success'>
                                        <p>Активна заявка на подтверждение
                                            статуса <?php echo YuristSettings::getStatusNameByCode($lastRequest['status']); ?>
                                            . Дождитесь
                                            проверки заявки модератором.</p>
                                    </div>
                                <?php else: ?>
                                    <?php if (0 == $user->settings->status): ?>
                                        <div class='alert alert-danger'>
                                            Вам пока не доступны все возможности сайта т.к. ваша квалификация не
                                            подтверждена.
                                            <?php echo CHtml::link('Подтвердить квалификацию', Yii::app()->createUrl('userStatusRequest/create'), ['class' => 'btn btn-xs btn-default']); ?>

                                        </div>
                                    <?php else: ?>
                                        <div class='flat-panel inside vert-margin20'>
                                            Ваш текущий статус:
                                            <strong>
                                                <?php echo $user->settings->getStatusName(); ?>
                                            </strong>
                                            <?php echo CHtml::link('Сменить статус', Yii::app()->createUrl('userStatusRequest/create'), ['class' => 'btn btn-xs btn-default']); ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($user->settings && $user->settings->isVerified): ?>
                            <div class="alert alert-info">
                                <span class="text-success glyphicon glyphicon-ok-sign"></span> Юрист подтвердил свое
                                образование, его квалификации можно доверять.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <p>
                    <img src="<?php echo $user->getAvatarUrl('big'); ?>" class='img-bordered' alt="<?php
                    echo CHtml::encode($user->getNameOrCompany());
                    ?>" title="<?php
                    echo CHtml::encode($user->getNameOrCompany());
                    ?>" itemprop="image"/>
                </p>
                <?php if ($user->id == Yii::app()->user->id): ?>
                    <?php if (User::ROLE_CLIENT == Yii::app()->user->role): ?>
                        <?php echo CHtml::link('Изменить пароль', Yii::app()->createUrl('user/changePassword', ['id' => Yii::app()->user->id])); ?>
                    <?php else: ?>
                        <?php echo CHtml::link('Редактировать свой профиль', Yii::app()->createUrl('user/update', ['id' => Yii::app()->user->id]), ['class' => 'btn btn-default btn-block']); ?>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (Yii::app()->user->isGuest): ?>
                    <?php echo CHtml::link('Задать вопрос юристу', Yii::app()->createUrl('/question/create/'), ['class' => 'btn btn-primary btn-block']); ?>
                <?php endif; ?>
                <?php if (!Yii::app()->user->isGuest): ?>
                    <?php if (User::ROLE_CLIENT == Yii::app()->user->role and $user->id == Yii::app()->user->id): ?>
                        <?php foreach ($chats as $ch): ?>
                            <?php echo CHtml::link('Открыть чат с ' . $ch->layer->getShortName(), Yii::app()->createUrl('/user/chat?chatId=' . $ch->chat_id), ['class' => 'btn btn-primary btn-block']); ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach ($chats as $ch): ?>

                            <?php echo CHtml::link(
                                'Открыть чат с ' . $ch->user->getShortName(),
                                Yii::app()->createUrl('/user/chat?chatId=' . $ch->chat_id),
                                ['class' => 'btn btn-primary btn-block']
                            ); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if (!$chat && (User::ROLE_CLIENT == Yii::app()->user->role)) : ?>
                        <?php echo CHtml::link('Начать чат с юристом', Yii::app()->createUrl('/user/chat'), ['class' => 'btn btn-primary btn-block']); ?>
                    <?php endif; ?>
                    <?php if ($chat && (User::ROLE_CLIENT == Yii::app()->user->role)) : ?>
                        <?php echo CHtml::link('Продолжить чат с юристом', Yii::app()->createUrl('/user/chat?chatId=' . $chat->chat_id), ['class' => 'btn btn-primary btn-block']); ?>
                    <?php endif; ?>

                <?php endif; ?>

                <?php
                if (User::ROLE_ROOT == Yii::app()->user->role) {
                    echo CHtml::link('Смотреть статистику ответов по месяцам', Yii::app()->createUrl('user/stats', ['userId' => $user->id]), ['class' => 'btn btn-block btn-default']);
                }
                ?>

                <?php if (!Yii::app()->user->isGuest && Yii::app()->user->id != $user->id && !Yii::app()->user->checkAccess(User::ROLE_JURIST) ): ?>
                    <?php echo CHtml::link('Оставить отзыв о юристе', Yii::app()->createUrl('user/testimonial', ['id' => $user->id]), ['class' => 'btn btn-block btn-info']); ?>
                <?php endif; ?>

            </div>
            <div class="col-sm-8">

                <div class="row">
                    <div class="col-md-12">
                        <?php if ($user->settings->hello): ?>
                            <div class="">
                                <blockquote>
                                    <?php echo CHtml::encode($user->settings->hello); ?>
                                </blockquote>
                            </div>
                            <hr>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row vert-margin30">

                    <?php if (User::ROLE_JURIST == $user->role): ?>
                        <div class="col-sm-3 col-xs-6 center-align">
                            <p>Консультаций:</p>
                            <?php
                            $answersCountInt = $user->answersCount;
                            $answersCount = str_pad((string) $answersCountInt, (strlen($answersCountInt) > 4) ? strlen($answersCountInt) : 4, '0', STR_PAD_LEFT);
                            $numbers = str_split($answersCount);

                            $karmaCount = str_pad((string) $user->karma, (strlen($user->karma) > 3) ? strlen($user->karma) : 3, '0', STR_PAD_LEFT);
                            $numbersKarma = str_split($karmaCount);

                            $testimonialsCount = $user->commentsCount;
                            $testimonialsCount = str_pad((string) $testimonialsCount, (strlen($testimonialsCount) > 4) ? strlen($testimonialsCount) : 3, '0', STR_PAD_LEFT);
                            $numbersTestimonials = str_split($testimonialsCount);

                            $rating = $user->getRating();
                            ?>

                            <p class="kpi-counter">
                                <?php foreach ($numbers as $num): ?><span><?php echo $num; ?></span><?php endforeach; ?>
                                <br/>
                            </p>
                        </div>

                        <div class="col-sm-3 col-xs-6 center-align">
                            <p><abbr title="Количество благодарностей за полезный ответ">Благодарностей:</abbr></p>
                            <p class="kpi-counter">
                                <?php foreach ($numbersKarma as $num): ?>
                                    <span><?php echo $num; ?></span><?php endforeach; ?><br/>
                            </p>
                        </div>

                        <div class="col-sm-3 col-xs-6 center-align">
                            <p><abbr title="Количество отзывов">Отзывов:</abbr></p>
                            <p class="kpi-counter">
                                <?php foreach ($numbersTestimonials as $num): ?>
                                    <span><?php echo $num; ?></span><?php endforeach; ?><br/>
                            </p>
                        </div>

                        <div class="col-sm-3 col-xs-6 center-align">
                            <p><abbr title="Средняя оценка по отзывам">Рейтинг:</abbr></p>
                            <p class="kpi-counter">
                                <span><?php echo $rating; ?></span><br/>
                            </p>
                        </div>

                        <hr/>
                    <?php endif; ?>
                </div>


                <?php if (User::ROLE_JURIST == $user->role): ?>

                    <h3 class="left-align">Контакты</h3>
                    <div class='row'>
                        <div class="col-md-4">
                            <p>
                                <span class="glyphicon glyphicon-map-marker"></span> <?php echo $user->town->name; ?></span>
                            </p>
                        </div>

                        <?php if ($user->settings->phoneVisible): ?>
                            <div class="col-md-4">
                                <p>
                                    <strong><span class="glyphicon glyphicon-earphone"
                                                  aria-hidden="true"></span></strong>
                                    <?php echo $user->settings->phoneVisible; ?>

                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if ($user->settings->emailVisible): ?>
                            <div class="col-md-4">
                                <p>
                                    <strong><span class="glyphicon glyphicon-envelope"
                                                  aria-hidden="true"></span></strong>
                                    <?php echo CHtml::encode($user->settings->emailVisible); ?>

                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if ($user->settings->site): ?>
                            <div class="col-md-4">
                                <p>
                                    <strong><span class="glyphicon glyphicon-globe"
                                                  aria-hidden="true"></span></strong> <?php echo CHtml::link(CHtml::encode($user->settings->site), CHtml::encode($user->settings->site), ['target' => '_blank', 'rel' => 'nofollow']); ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if (!$user->settings->site && !$user->settings->emailVisible && !$user->settings->phoneVisible && $user->settings->status != YuristSettings::STATUS_COMPANY): ?>
                            К сожалению, юрист не указал своих контактных данных

                        <?php endif; ?>
                        <?php if ($user->settings->status == YuristSettings::STATUS_COMPANY):?>
                            <div class="col-md-4">
                                <p>
                                    <strong><span class="glyphicon glyphicon-home"
                                                  aria-hidden="true"></span></strong> <?php echo CHtml::encode($user->settings->address); ?>
                                </p>
                            </div>

                        <?php endif; ?>

                    </div>

                    <hr/>

                    <?php if ($user->settings->description): ?>
                        <h3 class="left-align">Специалист о себе:</h3>
                        <p><?php echo CHtml::encode($user->settings->description); ?></p>

                        <?php if ($user->registerDate): ?>
                            <p>На сайте с: <?php echo DateHelper::invertDate($user->registerDate); ?></p>
                        <?php endif; ?>

                        <hr/>
                    <?php endif; ?>

                <?php endif; ?>

                <?php if (User::ROLE_JURIST == $user->role): ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if ($user->settings->education): ?>
                                <div class='vert-margin20'>
                                    <h3 class="left-align">Образование
                                        <?php if ($user->settings && $user->settings->isVerified): ?>
                                            <span class="text-success glyphicon glyphicon-ok-sign">Подтверждено</span>
                                        <?php endif; ?>
                                    </h3>
                                    <p>
                                        <?php if ($user->settings->education) {
                                echo '<b>Специальность:</b> ' . $user->settings->education . ' ';
                            } ?>
                                        <br/>
                                        <?php if ($user->settings->vuz) {
                                echo '<b>ВУЗ:</b> ' . $user->settings->vuz . ', ';
                            } ?>
                                        <br/>
                                        <?php if ($user->settings->vuzTownId) {
                                echo '<b>Город:</b> ' . $user->settings->vuzTown->name . ' ';
                            } ?>
                                        <br/>
                                        <?php if ($user->settings->educationYear) {
                                echo '<b>Год окончания:</b> ' . $user->settings->educationYear . ' ';
                            } ?>

                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-12">
                            <?php if ($user->categories): ?>
                                <div class='vert-margin20'>
                                    <h3 class="left-align">Специализации</h3>

                                    <?php foreach ($user->categories as $cat): ?>
                                        <span class="yurist-directions-item"><?php echo $cat->name; ?></span>
                                    <?php endforeach; ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </div>


                    <?php if ($user->settings->priceConsult > 0 || $user->settings->priceDoc > 0): ?>
                        <hr/>
                        <div class='vert-margin20'>
                            <h3 class="left-align">Информация о платных услугах</h3>
                            <?php if ($user->settings->priceConsult > 0): ?>
                                <p>Консультация от <?php echo MoneyFormat::rubles($user->settings->priceConsult); ?> руб.</p>
                            <?php endif; ?>
                            <?php if ($user->settings->priceDoc > 0): ?>
                                <p>Составление документа от <?php echo MoneyFormat::rubles($user->settings->priceDoc); ?> руб.
                                    <?php if (8 == $user->id): ?>
                                        <?php echo CHtml::link('Заказать документ', Yii::app()->createUrl('question/docs', ['juristId' => $user->id])); ?>
                                    <?php endif; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset(Yii::app()->params['donatesEnabled']) && true == Yii::app()->params['donatesEnabled']): ?>
                        <?php if (User::ROLE_CLIENT == Yii::app()->user->role || User::ROLE_ROOT == Yii::app()->user->role): ?>
                            <div class="vert-margin30"></div>
                            <div class='donate-block'>
                                <h3>Оплатить услуги юриста</h3>
                                <?php $this->renderPartial('application.views.question._donateForm', [
                                    'target' => 'Благодарность юристу ' . CHtml::encode($user->name) . ' ' . CHtml::encode($user->lastName),
                                    'successUrl' => Yii::app()->createUrl('user/view', ['id' => $user->id]),
                                    'donateSum' => ($user->settings->priceConsult > 0) ? MoneyFormat::rubles($user->settings->priceConsult) : 500,
                                ]); ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>


                <?php endif; ?>

                <div class="row">
                    <div class="col-sm-12">
                        <?php if (sizeof($questions) > 0): ?>
                            <?php if (User::ROLE_CLIENT == $user->role): ?>
                                <hr>
                                <h2 class="vert-margin20">Мои вопросы</h2>
                            <?php else: ?>
                                <hr>
                                <h2 class="vert-margin20">Последние вопросы, на которые ответил
                                    юрист</h2>
                            <?php endif; ?>


                            <?php foreach ($questions as $question): ?>
                                <div class="row question-list-item">
                                    <div class="col-sm-12">
                                        <p style="font-size:1.1em;">
                                            <small>
                                                <?php echo CHtml::link(CHtml::encode(StringHelper::mb_ucfirst($question['title'])), Yii::app()->createUrl('question/view', ['id' => $question['id']])); ?>
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($testimonialsDataProvider->totalItemCount > 0): ?>
                    <h2>Последние отзывы</h2>
                    <?php
                    $this->widget('zii.widgets.CListView', [
                        'dataProvider' => $testimonialsDataProvider,
                        'itemView' => 'application.views.comment._viewUser',
                        'emptyText' => 'Не найдено ни одного отзыва',
                        'summaryText' => '',
                        'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
                    ]);
                    ?>
                    <p class="text-center">
                        <?php echo CHtml::link('Все отзывы', Yii::app()->createUrl('user/testimonials', ['id' => $user->id])); ?>
                    </p>
                <?php endif; ?>

                <?php if (User::ROLE_CLIENT == Yii::app()->user->role && Yii::app()->user->id == $user->id): ?>
                    <hr>
                    <h2>Мои заказы документов</h2>
                    <table class="table table-bordered">
                        <?php
                        $this->widget('zii.widgets.CListView', [
                            'dataProvider' => $ordersDataProvider,
                            'itemView' => 'application.views.order._view',
                            'emptyText' => 'Не найдено ни одного заказа',
                            'summaryText' => 'Показаны заказы с {start} до {end}, всего {count}',
                            'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
                        ]);
                        ?>
                    </table>
                <?php endif; ?>
            </div> <!-- Конец col-sm-8 -->
        </div>
    </div>

</div>
