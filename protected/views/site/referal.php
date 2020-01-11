<?php
$this->setPageTitle("Реферальная программа." . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag("Получайте деньги за каждого приглашенного зарегистрировавшегося пользователя", 'description');
?>

<script src="https://yastatic.net/share2/share.js" async="async"></script>


<h1>Реферальная программа</h1>

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
    <div class="ya-share2" data-services="vkontakte,twitter,facebook,gplus,moimir,odnoklassniki,viber,telegram,whatsapp" data-image="<?php echo Yii::app()->urlManager->baseUrl; ?>/pics/2017/100_yuristov_logo_blue.jpg" data-counter data-title="Юридическая консультация онлайн. Задайте вопрос юристу и получите ответ в течение 15 минут" data-url="<?php echo Yii::app()->urlManager->baseUrl . '/?ref=' . Yii::app()->user->id; ?>"></div>
</div>

<table class="table table-bordered vert-margin20">
    <tr>
        <th>Кого приглашаете:</th>
        <th>Условия</th>
    </tr>
    <tr>
        <td>Клиент</td>
        <td>Приглашенный пользователь должен:
            <ul>
                <li>задать вопрос через форму на сайте</li>
                <li>подтвердить свою почту</li>
            </ul>
			<strong>За приглашенного клиента вы получите: <?php echo MoneyFormat::rubles(Yii::app()->params['bonuses'][User::ROLE_CLIENT]); ?> руб.</strong>
        </td>
    </tr>
    <tr>
        <td>Юрист</td>
        <td>Приглашенный пользователь должен:
            <ul>
                <li>зарегистрироваться</li>
                <li>подтвердить свою почту</li>
                <li>подтвердить свой статус юриста или адвоката</li>
                <li>дать не менее 25 ответов на вопросы пользователей</li>
            </ul>
		<strong>За приглашенного юриста вы получите: <?php echo MoneyFormat::rubles(Yii::app()->params['bonuses'][User::ROLE_JURIST]); ?> руб. </strong>
        </td>
    </tr>
</table>

<ul class="vert-margin40">
    <li>Средства на пользовательский счёт зачисляются раз в сутки в автоматическом режиме.</li>
    <li>Средства с пользовательского счета выводятся на баланс номера мобильного телефона</li>
    <li>Минимальная сумма на вывод — 500 рублей</li>
    <li>Максимальная сумма на вывод за одну операцию — 5000 рублей</li>
    <li>Для того, чтобы вывести средства, в личном кабинете нужно сформировать заявку на 
        вывод, указав сумму и номер телефона для зачисления средств.</li>
    <li>Кнопка "Вывести средства" появится у Вас по достижении на балансе 500 рублей.</li>
    <li>Заявки на вывод средств обрабатываются в течение двух рабочих дней.</li>
</ul>

<h3 class="vert-margin20">Где размещать ссылку? </h3>
<div class="row vert-margin40">
    <div class="col-sm-3">
        <p>
            <strong>Мессенджеры:</strong><br />
            Whatsapp<br />
            Viber<br />
            Telegram
        </p>
    </div>
    <div class="col-sm-4">
        <p>
            <strong>Социальные сети:</strong><br />
            Вконтакте (<a href="https://vk.com" rel="nofollow">vk.com</a>),<br/>
            Одноклассники (<a href="https://ok.ru" rel="nofollow">ok.ru</a>),<br/>
            Facebook (<a href="https://fb.com" rel="nofollow">fb.com</a>),<br/>
            Google+ (<a href="https://plus.google.com" rel="nofollow">plus.google.com</a>),<br/>
            Мой@Мир (<a href="https://my.mail.ru" rel="nofollow">my.mail.ru</a>)
        </p>
    </div>
    <div class="col-sm-5">
        <p><strong>Сервисы блогов и микроблогов:</strong><br />
            Twitter (<a href="https://twitter.com" rel="nofollow">twitter.com</a>),<br/>
            Живой Журнал (<a href="https://livejournal.com" rel="nofollow">livejournal.com</a>),<br/>
            Блог.ру (<a href="https://blog.ru" rel="nofollow">blog.ru</a>),<br/>
            Blogger.com (<a href="https://blogger.com" rel="nofollow">blogger.com</a>),<br/>
            MyPage (<a href="https://mypage.ru" rel="nofollow">mypage.ru</a>),<br/>
            LiveInternet (<a href="https://liveinternet.ru" rel="nofollow">liveinternet.ru</a>)
        </p>
    </div>
</div>

<div class="vert-margin40 text-center">
    <h3>Для вашего удобства мы подготовили ссылки на популярные сервисы</h3>
    <div class="ya-share2" data-services="vkontakte,twitter,facebook,gplus,moimir,odnoklassniki,viber,telegram,whatsapp" data-image="<?php echo Yii::app()->urlManager->baseUrl; ?>/pics/2017/100_yuristov_logo_blue.jpg" data-counter data-title="Юридическая консультация онлайн. Задайте вопрос юристу и получите ответ в течение 15 минут" data-url="<?php echo Yii::app()->urlManager->baseUrl . '/?ref=' . Yii::app()->user->id; ?>"></div>
</div>

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