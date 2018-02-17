<?php
$this->setPageTitle("Юридическая партнерская программа." . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag("Получайте деньги за каждого приглашенного зарегистрировавшегося пользователя", 'description');
?>

<script src="https://yastatic.net/share2/share.js" async="async"></script>


<h1>Юридическая партнёрская программа</h1>

<p class="vert-margin40 lead text-center">
    Зарабатывайте, приглашая своих знакомых с помощью реферальной ссылки
</p>



<?php if (Yii::app()->user->isGuest): ?>
    <p>
        Реферальная ссылка доступна в личном кабинете. 
        <?php echo CHtml::link('Авторизоваться', Yii::app()->createUrl('site/login')); ?> или 
        <?php echo CHtml::link('зарегистрироваться', Yii::app()->createUrl('user/create')); ?>
    </p>
<?php else: ?>
    <div class="vert-margin20 blue-block inside">
        <div class="row">
            <div class="col-sm-6 text-right">
                <p class="lead" style="margin-bottom:0;">Ваша реферальная ссылка:</p> 
            </div>
            <div class="col-sm-6">
                <?php echo CHtml::textField('refLink', Yii::app()->urlManager->baseUrl . '/?ref=' . Yii::app()->user->id, ['class' => 'form-control']); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<p class="vert-margin20">
    Размещайте эту ссылку в социальных сетях, форумах, блогах и других сайтах. Получайте вознаграждение на телефон за пользователей,
    которые зарегистрируются по вашей ссылке.  
</p>

<div class="vert-margin40 text-center">
    <h3>Поделиться ссылкой с друзьями в один клик:</h3>
    <div class="ya-share2" data-services="vkontakte,twitter,facebook,gplus" data-image="<?php echo Yii::app()->urlManager->baseUrl;?>/pics/100_yuristov_logo_blue.jpg" data-counter data-title="Юридическая консультация онлайн" data-description="Задайте вопрос юристу и получите ответ в течение 15 минут" data-url="<?php echo Yii::app()->urlManager->baseUrl . '/?ref=' . Yii::app()->user->id;?>"></div>
</div>

<table class="table table-bordered vert-margin20">
    <tr>
        <th>Вознаграждение</th>
        <th>Тип пользователя</th>
        <th>Условия</th>
    </tr>
    <tr>
        <td><?php echo Yii::app()->params['bonuses'][User::ROLE_CLIENT]; ?> руб.</td>
        <td>Клиент</td>
        <td>Приглашенный пользователь должен:
            <ul>
                <li>задать вопрос через форму на сайте</li>
                <li>подтвердить свою почту</li>
            </ul>
        </td>
    </tr>
    <tr>
        <td><?php echo Yii::app()->params['bonuses'][User::ROLE_JURIST]; ?> руб.</td>
        <td>Юрист</td>
        <td>Приглашенный пользователь должен:
            <ul>
                <li>зарегистрироваться</li>
                <li>подтвердить свою почту</li>
                <li>подтвердить свой статус юриста или адвоката</li>
                <li>дать не менее 10 ответов на вопросы пользователей</li>
            </ul>
        </td>
    </tr>
</table>

<ul class="vert-margin40">
    <li>Средства на пользовательский счёт зачисляются раз в сутки в автоматическом режиме.</li>
    <li>Средства с пользовательского счета выводятся на баланс номера мобильного телефона</li>
    <li>Минимальная сумма на вывод — 500 рублей</li>
    <li>Максимальная сумма на вывод за одну операцию — 5000 рублей</li>
</ul>

<?php if (!Yii::app()->user->isGuest): ?>
    <h2 class="vert-margin20">Приглашённые вами пользователи</h2>

    <?php if (sizeof($referals) == 0): ?>
        <p>По вашей реферальной ссылке пока никто не зарегистрировался</p>
    <?php else: ?>
        <table class="table table-bordered">
            <?php foreach ($referals as $referal): ?>
                <tr>
                    <td>
                        <?php echo CustomFuncs::niceDate($referal->registerDate, false, false); ?>
                    </td>
                    <td>
                        <?php echo CHtml::encode($referal->name); ?>
                    </td>
                    <td>
                        <p><?php echo $referal->getRoleName(); ?>
                            <small>
                                <?php if ($referal->role == User::ROLE_JURIST): ?>
                                    <?php
                                    $answersCount = $referal->answersCount;
                                    $isVerified = $referal->settings->isVerified;
                                    ?>
                                    <br />
                                    Подтверждение статуса: <?php echo ($isVerified == 1) ? 'да' : 'нет'; ?><br />
                                    Ответов: <?php echo $answersCount; ?>
                                <?php endif; ?>
                                <?php if ($referal->role == User::ROLE_CLIENT): ?>
                                    <br />
                                    Вопросов: <?php echo $referal->questionsCount; ?>
                                <?php endif; ?>
                            </small>
                        </p>
                    </td>
                    <td class="text-right">
                        <?php echo (int) $referal->referalOk(); ?> руб.
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>


<?php endif; ?>


<?php if (Yii::app()->user->isGuest): ?>
    <h2>Начните зарабатывать!</h2>
    <p class="text-center">
        <?php echo CHtml::link('Авторизоваться', Yii::app()->createUrl('site/login')); ?> или 
        <?php echo CHtml::link('зарегистрироваться', Yii::app()->createUrl('user/create')); ?>
    </p>
<?php endif; ?>