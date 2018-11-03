<?php
/* @var $this UserController */
/* @var $model User */
/**
 * Просмотр профиля юриста
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

$this->breadcrumbs[] = CHtml::encode($user->name . ' ' . $user->lastName);



$title = CHtml::encode($user->name . ' ' . $user->name2 . ' ' . $user->lastName);

if ($user->settings) {
    $title = $user->settings->getStatusName() . ' ' . $title;
}

$title .= ', ' . $user->town->name;

$this->setPageTitle($title . '. ' . Yii::app()->name);

// формируем метаописание профиля
$pageDescription = '';
if ($user->settings) {
    // для юриста выведем его статус (юрист/адвокат)
    $pageDescription .= $user->settings->getStatusName() . ' ';
}
$pageDescription .= CHtml::encode($user->name . ' ' . $user->name2 . ' ' . $user->lastName) . '. ';
if ($user->town) {
    $pageDescription .= $user->town->name;
}
Yii::app()->clientScript->registerMetaTag($pageDescription, 'description');


if (Yii::app()->user->id != $user->id) {
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink' => CHtml::link('100 Юристов', "/"),
        'separator' => ' / ',
        'links' => $this->breadcrumbs,
    ));
}
?>

<div class="vert-margin30"  itemscope itemtype="http://schema.org/Person">
    <h1 class='vert-margin30'>
        <?php if ($user->settings): ?>
            <span itemprop="jobTitle">
                <?php echo $user->settings->getStatusName(); ?> 
            </span>
        <?php endif; ?>

        <span itemprop="name">
            <?php
            echo CHtml::encode($user->name . ' ' . $user->name2 . ' ' . $user->lastName);
            ?>
        </span>
        <?php if ($user->settings && $user->settings->isVerified): ?>
            <span class="text-success glyphicon glyphicon-ok-sign"></span>
        <?php endif; ?>
    </h1>


    <?php if ($user->id == Yii::app()->user->id): ?>
        <?php if (Yii::app()->user->role == User::ROLE_JURIST): ?>
            <?php if ($lastRequest && $lastRequest['isVerified'] == 0): ?>
                <div class='alert alert-success'>
                    <p>Активна заявка на подтверждение статуса <?php echo YuristSettings::getStatusNameByCode($lastRequest['status']); ?>. Дождитесь проверки заявки модератором.</p>
                </div>
            <?php else: ?>
                <?php if ($user->settings->status == 0): ?>
                    <div class='alert alert-danger'>
                        Вам пока не доступны все возможности сайта т.к. ваша квалификация не подтверждена.
                        <?php echo CHtml::link('Подтвердить квалификацию', Yii::app()->createUrl('userStatusRequest/create'), array('class' => 'btn btn-xs btn-default')); ?>

                    </div>
                <?php else: ?>
                    <div class='flat-panel inside vert-margin20'>
                        Ваш текущий статус:
                        <strong>
                            <?php echo $user->settings->getStatusName(); ?>
                        </strong>
                        <?php echo CHtml::link('Сменить статус', Yii::app()->createUrl('userStatusRequest/create'), array('class' => 'btn btn-xs btn-default')); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>

    <div class='vert-margin20'>
        <div class="row">
            <div class="col-sm-4 center-align">
                <p>
                    <img src="<?php echo $user->getAvatarUrl('big'); ?>" class='' alt="<?php
                    echo CHtml::encode($user->name . ' ' . $user->name2 . ' ' . $user->lastName);
                    ?>" title="<?php
                         echo CHtml::encode($user->name . ' ' . $user->name2 . ' ' . $user->lastName);
                         ?>" itemprop="image" />
                </p>  
                <?php if ($user->id == Yii::app()->user->id): ?>
                    <?php if (Yii::app()->user->role == User::ROLE_CLIENT): ?>
                        <?php echo CHtml::link('Изменить пароль', Yii::app()->createUrl('user/changePassword', array('id' => Yii::app()->user->id))); ?>
                    <?php else: ?>
                        <?php echo CHtml::link('Редактировать профиль', Yii::app()->createUrl('user/update', array('id' => Yii::app()->user->id))); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-md-12">
                        <?php if ($user->settings->hello): ?>
                            <div class="alert alert-info">
                                <?php echo CHtml::encode($user->settings->hello); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row vert-margin30">
                    <div class="col-sm-4 col-xs-12 center-align">
                        <p>
                            <strong>Город:</strong><br /><?php echo $user->town->name; ?>
                        </p>
                        <?php if ($user->registerDate): ?>
                            <p>На сайте с <?php echo CustomFuncs::invertDate($user->registerDate); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($user->role == User::ROLE_JURIST): ?> 
                        <div class="col-sm-4 col-xs-6 center-align">
                            <p>Ответов</p>
                            <?php
                            $answersCountInt = $user->answersCount;
                            $answersCount = str_pad((string) $answersCountInt, (strlen($answersCountInt) > 4) ? strlen($answersCountInt) : 4, '0', STR_PAD_LEFT);
                            $numbers = str_split($answersCount);

                            $karmaCount = str_pad((string) $user->karma, (strlen($user->karma) > 3) ? strlen($user->karma) : 3, '0', STR_PAD_LEFT);
                            ;
                            $numbersKarma = str_split($karmaCount);
                            ?>

                            <p class="kpi-counter">
                                <?php foreach ($numbers as $num): ?><span><?php echo $num; ?></span><?php endforeach; ?><br />
                            </p>

                        </div>
                        <div class="col-sm-4 col-xs-6 center-align">
                            <p><abbr title="Количество благодарностей за полезный ответ">Карма</abbr></p>
                            <p class="kpi-counter">
                                <?php foreach ($numbersKarma as $num): ?><span><?php echo $num; ?></span><?php endforeach; ?><br />
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <hr/>

                <?php if ($user->role == User::ROLE_JURIST): ?>

                    <h3 class="left-align">Контакты</h3>
                    <div class='row'>                                                 

                        <?php if ($user->settings->phoneVisible): ?>
                            <div class="col-md-6">
                                <p>
                                    <strong><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span></strong> 
                                    <?php echo $user->settings->phoneVisible; ?>

                                </p> 
                            </div>
                        <?php endif; ?>

                        <?php if ($user->settings->emailVisible): ?>
                            <div class="col-md-6">
                                <p>
                                    <strong><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></strong> 
                                    <?php echo CHtml::encode($user->settings->emailVisible); ?>

                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if ($user->settings->site): ?>
                            <div class="col-md-6">
                                <p>
                                    <strong><span class="glyphicon glyphicon-globe" aria-hidden="true"></span></strong> <?php echo CHtml::link(CHtml::encode($user->settings->site), CHtml::encode($user->settings->site), array('target' => '_blank', 'rel' => 'nofollow')); ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if (!$user->settings->site && !$user->settings->emailVisible && !$user->settings->phoneVisible): ?>
                            К сожалению, юрист не указал своих контактных данных
                        <?php endif; ?>

                    </div>
                    <hr/>
                <?php endif; ?>

            </div>

        </div>
    </div>
    <?php if ($user->role == User::ROLE_JURIST): ?>

        <?php if ($user->settings->description): ?>
            <blockquote>
                    <h3 class="left-align">О себе:</h3>
                    <p><?php echo CHtml::encode($user->settings->description); ?></p>
            </blockquote>
            <hr />
        <?php endif; ?>


        <div class="row">
            <div class="col-sm-4">
                <?php if ($user->settings->education): ?>
                    <div class='vert-margin20'>             
                        <h3 class="left-align">Образование 
                            <?php if ($user->settings && $user->settings->isVerified): ?>
                                <span class="text-success glyphicon glyphicon-ok-sign"></span>
                            <?php endif; ?>
                        </h3> 
                        <p>
                            <?php if ($user->settings->education) echo $user->settings->education . ', '; ?><br />
                            <?php if ($user->settings->vuz) echo 'ВУЗ: ' . $user->settings->vuz . ', '; ?><br />
                            <?php if ($user->settings->vuzTownId) echo 'Город: ' . $user->settings->vuzTown->name . ', '; ?><br />
                            <?php if ($user->settings->educationYear) echo 'Год окончания: ' . $user->settings->educationYear . '.'; ?>

                        </p>
                    </div> 
                <?php endif; ?>
            </div>
            <div class="col-sm-8">
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

        <hr />

        <?php if ($user->settings->priceConsult > 0 || $user->settings->priceDoc > 0): ?>
            <div class='vert-margin20'> 
                <h3 class="left-align">Платные услуги</h3>
                <?php if ($user->settings->priceConsult > 0): ?>
                    <p>Консультация от <?php echo $user->settings->priceConsult; ?> руб.</p>
                <?php endif; ?>
                <?php if ($user->settings->priceDoc > 0): ?>
                    <p>Составление документа от <?php echo $user->settings->priceDoc; ?> руб.
                        <?php if ($user->id == 8): ?>
                            <?php echo CHtml::link('Заказать документ', Yii::app()->createUrl('question/docs', ['juristId' => $user->id])); ?>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>        
        <?php endif; ?>

    <?php endif; ?>        
</div>

<?php if (sizeof($questions) > 0): ?>
    <?php if ($user->role == User::ROLE_CLIENT): ?>
        <h2 class="header-block-light-grey vert-margin20">Мои вопросы</h2>
    <?php else: ?>
        <h2 class="header-block-light-grey vert-margin20">Последние вопросы, на которые ответил юрист</h2>
    <?php endif; ?>


    <?php foreach ($questions as $question): ?>
        <div class="row question-list-item">
            <div class="col-sm-12">
                <p style="font-size:1.1em;">
                    <small>
                        <?php echo CHtml::link(CHtml::encode(CustomFuncs::mb_ucfirst($question['title'])), Yii::app()->createUrl('question/view', array('id' => $question['id']))); ?>
                    </small>
                </p>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (Yii::app()->user->role == User::ROLE_CLIENT && Yii::app()->user->id == $user->id): ?>
    <h2>Мои заказы документов</h2>
    <table class="table table-bordered">
        <?php
        $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $ordersDataProvider,
            'itemView' => 'application.views.order._view',
            'emptyText' => 'Не найдено ни одного заказа',
            'summaryText' => 'Показаны заказы с {start} до {end}, всего {count}',
            'pager' => array('class' => 'GTLinkPager'), //we use own pager with russian words
        ));
        ?>
    </table>
<?php endif; ?>

<?php
if (Yii::app()->user->role == User::ROLE_ROOT) {
    echo CHtml::link('Смотреть статистику ответов по месяцам', Yii::app()->createUrl('user/stats', array('userId' => $user->id)), array('class' => 'btn btn-xs btn-default'));
}
?>

