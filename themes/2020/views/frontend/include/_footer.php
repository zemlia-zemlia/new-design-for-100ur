<?php
use App\models\User;
?>


<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-7 col-sm-4 col-lg-3">
                <div class="footer__wrap">
                    <a href="" class="footer__logo img">
                        <img src="/img/footer-logo.png" alt="">
                    </a>
                    <ul class="footer__list">
                        <li class="footer__list-item">
                        <?= ($_SERVER['REQUEST_URI'] != '/site/about/') ?
                            CHtml::link('О проекте', Yii::app()->createUrl('/site/about/'), ['class' => 'footer__list-link']) :
                            '<span class="active">О проекте</span>'; ?>
                        </li>
                        <li class="footer__list-item">
                            <?= ($_SERVER['REQUEST_URI'] != '/site/contacts/') ? CHtml::link('Контакты',
                                Yii::app()->createUrl('/site/contacts'), ['class' => 'footer__list-link']) : '<span class="active">Контакты</span>'; ?>

                        </li>
                        <li class="footer__list-item">
                            <?php echo ($_SERVER['REQUEST_URI'] != '/site/offer/') ? CHtml::link('Пользовательское соглашение',
                                Yii::app()->createUrl('/site/offer/'), ['class' => 'footer__list-link']) : '<span class="active">Пользовательское соглашение</span>'; ?>

                        </li>
                        <li class="footer__list-item">
                            <a href="" class="footer__list-link">Реферальная программа</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-8 col-sm-4 col-lg-3">
                <div class="footer__wrap footer__wrap-padding">
                    <ul class="footer__list">
                        <li class="footer__list-item">
                            <?= ($_SERVER['REQUEST_URI'] != '/region/') ? CHtml::link('Каталог юристов',
                                Yii::app()->createUrl('/region/country', ['countryAlias' => 'russia']), ['class' => 'footer__list-link']) :
                                '<span class="active">География</span>'; ?>

                        </li>
                        <li class="footer__list-item">
                            <?= ($_SERVER['REQUEST_URI'] != '/q/') ? CHtml::link('Архив вопросов',
                                Yii::app()->createUrl('/question/index'), ['class' => 'footer__list-link']) : '<span class="active">Архив вопросов</span>'; ?>
                        </li>
                        <li class="footer__list-item">
                            <?= ($_SERVER['REQUEST_URI'] != '/site/goryachaya_liniya/') ? CHtml::link('Горячая линия',
                                Yii::app()->createUrl('/site/goryachaya_liniya/'), ['class' => 'footer__list-link']) : '<span class="active">Горячая линия</span>'; ?>
                        </li>
                        <li class="footer__list-item">
                            <?= ($_SERVER['REQUEST_URI'] != '/blog/') ? CHtml::link('Новости',
                                Yii::app()->createUrl('/blog'), ['class' => 'footer__list-link']) : '<span class="active">Блог проекта</span>'; ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-8 col-sm-4 col-lg-3">
                <div class="footer__wrap footer__wrap-padding">
                    <ul class="footer__list">
                        <li class="footer__list-item">
                            <?= ($_SERVER['REQUEST_URI'] != '/user/create/role/10/') ? CHtml::link('Регистрация на сайте',
                                Yii::app()->createUrl('/user/create/role/10/'), ['class' => 'footer__list-link']) : '<span class="active">Регистрация на сайте</span>'; ?>
                        </li>
                        <li class="footer__list-item">
                            <?= ($_SERVER['REQUEST_URI'] != '/site/crm/') ? CHtml::link('CRM для юристов',
                                Yii::app()->createUrl('/site/crm/'), ['class' => 'footer__list-link']) : '<span class="active">CRM для юристов</span>'; ?>
                        </li>
                        <li class="footer__list-item">
                            <?= ($_SERVER['REQUEST_URI'] != '/site/lead/') ? CHtml::link('Юридические заявки',
                                Yii::app()->createUrl('/site/lead/'), ['class' => 'footer__list-link']) : '<span class="active">Юридические заявки</span>'; ?>
                        </li>
                        <li class="footer__list-item">
                            <?= ($_SERVER['REQUEST_URI'] != '/site/yuristam/') ? CHtml::link('Зачем отвечать на вопросы',
                                Yii::app()->createUrl('/site/yuristam/'), ['class' => 'footer__list-link']) : '<span class="active">Зачем отвечать на вопросы</span>'; ?>
                        </li>
                        <li class="footer__list-item">
                        <?= ($_SERVER['REQUEST_URI'] != '/site/referal/') ? CHtml::link('Реферальная программа',
                            Yii::app()->createUrl('/site/referal/'), ['class' => 'footer__list-link']) : '<span class="active">Реферальная программа</span>'; ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-12 col-lg-3">
                <div class="footer__wrap footer__wrap-social">
                    <ul class="footer__social">
                        <li class="footer__social-item">
                            <a href="https://www.instagram.com/100yuristov/" class="footer__social-link img">
                                <img src="/img/footer-social-link-1.png" alt="">
                            </a>
                        </li>
                        <li class="footer__social-item">
                            <a href="https://vk.com/sto_yuristov" class="footer__social-link img">
                                <img src="/img/footer-social-link-2.png" alt="">
                            </a>
                        </li>
                        <li class="footer__social-item">
                            <a href="https://ok.ru/group/53087450366125" class="footer__social-link img">
                                <img src="/img/footer-social-link-3.png" alt="">
                            </a>
                        </li>
                        <li class="footer__social-item">
                            <a href="https://www.youtube.com/channel/UCgleswVaxaLKwL-MeGDmtfQ" class="footer__social-link img">
                                <img src="/img/footer-social-link-4.png" alt="">
                            </a>
                        </li>
                        <li class="footer__social-item">
                            <a href="https://www.facebook.com/100-%D0%AE%D1%80%D0%B8%D1%81%D1%82%D0%BE%D0%B2-1384104981880799/" class="footer__social-link img">
                                <img src="/img/footer-social-link-5.png" alt="">
                            </a>
                        </li>
                        <li class="footer__social-item">
                            <a href="https://twitter.com/stoyuristov" class="footer__social-link img">
                                <img src="/img/footer-social-link-6.png" alt="">
                            </a>
                        </li>
                    </ul>
                    <div class="footer__branches">
                        <div class="footer__branches-title">Филиалы:</div>
                        <ul class="footer__branches-list">
                            <li class="footer__branches-item">Санкт-Петербург, ул. Достоевского, д. 25</li>
                            <li class="footer__branches-item">Нижний Новгород, ул. Новая, д. 28</li>
                            <li class="footer__branches-item">Екатеринбург, ул. 8 марта, д. 142</li>
                            <li class="footer__branches-item">Ростов-на-Дону, ул. Красноармейская, д. 142/50</li>
                            <li class="footer__branches-item">Краснодар, ул. Московская, д. 148</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>




<?php if (Yii::app()->user->isGuest): ?>
    <?php require_once __DIR__ . '/robot_widget.php'; ?>
<?php endif; ?>

</body>
</html>