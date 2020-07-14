<?php
$this->setPageTitle('Контакты юридических центров. ' . Yii::app()->name);

//Yii::app()->clientScript->registerScriptFile('https://api-maps.yandex.ru/2.0-stable/?load=package.standard&lang=ru-RU', CClientScript::POS_HEAD);
?>

<!-- Crm-system -->
<main class="main">
    <div class="container">
        <h2 class="main__title">Контакты</h2>
        <div class="main__subtitle contacts__subtitle">Портал "100 Юристов" предоставляет юридические консультации онлайн для всех жителей РФ, Беларуси и Украины.</div>

        <div class="contacts__wrap">
            <div class="contacts__title">Адрес головного офиса</div>
            <div class="contacts__desc">100 Юристов — юридический портал</div>
            <div class="contacts__item">
                <div class="contacts__item-img img">
                    <img src="/img/contacts-item-img-1.png" alt="">
                </div>
                <div class="contacts__item-value">Москва, Шлюзовая набережная д. 6 стр. 4</div>
            </div>
            <div class="contacts__item">
                <div class="contacts__item-img img">
                    <img src="/img/contacts-item-img-2.png" alt="">
                </div>
                <a href="tel:88005006185" class="contacts__item-value">8-800-500-61-85</a>
            </div>
        </div>

        <div class="contacts__map">
            <iframe src="http://yandex.ru/map-widget/v1/?um=constructor%3Ad6fe4decd13780d75c7eda3d95ff04393506192eea5129eb81f287ecb8179ef5&amp;source=constructor" frameborder="0"></iframe>
        </div>
    </div>
</main>
