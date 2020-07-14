<?php

use App\models\User;

$this->setPageTitle('Продажа юридических заявок и лидов на консультацию');
Yii::app()->clientScript->registerMetaTag('Клиенты для юристов во всех отраслях права - юридические звонки, заявки, приходы. Можете получать горячих клиентов уже сегодня.', 'description');
?>

<!-- Selling Leads -->
<main class="main">
    <section class="selling__main">
        <div class="selling__main-bg">
            <img src="/img/selling-leads-main-bg.png" alt="" class="selling-main-big">
            <img src="/img/selling--main-bg-mob.png" alt="" class="selling-main-bg-mob">
        </div>
        <div class="container">
            <div class="selling__main-content">
                <h1 class="selling__main-title">Продажа юридических лидов для всех регионов РФ</h1>
                <div class="selling__main-subtitle">Покупайте у нас заявки на консультацию юриста и работайте с ними без лишних проблем и забот.</div>

                <?php echo CHtml::link('Зарегистрироваться как покупатель лидов',
                    Yii::app()->createUrl('user/create', ['role' => User::ROLE_BUYER]), ['class' => 'main-btn selling__main-btn']); ?>
            </div>
        </div>
    </section>

    <section class="selling__desc">
        <div class="container">
            <div class="selling__desc-wrap">
                <div class="selling__desc-item">
                    <p>Работая с нами, вам больше не нужно будет тратить время  на продвижение своих услуг в интернете (создание сайта, ведение соц. сестей, анализ метрик, настройка рекламы и т.д.), чтобы получать клиентов.</p>
                </div>
                <div class="selling__desc-item selling__desc-item--bold">
                    <p>Мы будем искать вам клиентов, а вы будете продавать им свои юридические услуги.</p>
                </div>
                <div class="selling__desc-item">
                    <p>Покупайте у нас заявки на консультацию юриста и работайте с ними без лишних проблем.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="selling__example">
        <div class="container">
            <h2 class="selling__example-title section-title">Юридический лид, который вы покупаете, содержит:</h2>
            <div class="row justify-content-center">
                <div class="col-6 col-sm-3">
                    <div class="selling__example-benefits">
                        <div class="selling__example-benefits-img img">
                            <img src="/img/selling-example-benefits-img-1.png" alt="">
                        </div>
                        <div class="selling__example-benefits-title">Имя клиента</div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="selling__example-benefits">
                        <div class="selling__example-benefits-img img">
                            <img src="/img/selling-example-benefits-img-2.png" alt="">
                        </div>
                        <div class="selling__example-benefits-title">Номер телефона клиента</div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="selling__example-benefits">
                        <div class="selling__example-benefits-img img">
                            <img src="/img/selling-example-benefits-img-3.png" alt="">
                        </div>
                        <div class="selling__example-benefits-title">Город клиента</div>
                    </div>
                </div>
                <div class="col-6 col-sm-3">
                    <div class="selling__example-benefits">
                        <div class="selling__example-benefits-img img">
                            <img src="/img/selling-example-benefits-img-4.png" alt="">
                        </div>
                        <div class="selling__example-benefits-title">Текст вопроса клиента</div>
                    </div>
                </div>
            </div>
            <div class="selling__example-subtitle">Несколько примеров реальных лидов, которые получили наши клиенты:</div>
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <div class="selling__example-item">
                        <div class="selling__example-item-heading">
                            <div class="selling__example-item-location">Москва (Московская область)</div>
                            <a href="tel:+79096573723" class="selling__example-item-phone">+7-909-657-37-23</a>
                            <div class="selling__example-item-person">Ольга</div>
                        </div>
                        <div class="selling__example-item-desc">Подскажите, пожалуйста, как можно подать на алименты отца ребенка, если не состоим в браке и если он не хочет добровольно помогать материально? А в суде отказываются принимать заявление.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="selling__example-item">
                        <div class="selling__example-item-heading">
                            <div class="selling__example-item-location">Москва (Московская область)</div>
                            <a href="tel:+79096573723" class="selling__example-item-phone">+7-909-657-37-23</a>
                            <div class="selling__example-item-person">Ольга</div>
                        </div>
                        <div class="selling__example-item-desc">Подскажите, пожалуйста, как можно подать на алименты отца ребенка, если не состоим в браке и если он не хочет добровольно помогать материально? А в суде отказываются принимать заявление.</div>
                    </div>
                </div>
                <div class="w-100"></div>
                <div class="col-md-6">
                    <div class="selling__example-item">
                        <div class="selling__example-item-heading">
                            <div class="selling__example-item-location">Москва (Московская область)</div>
                            <a href="tel:+79096573723" class="selling__example-item-phone">+7-909-657-37-23</a>
                            <div class="selling__example-item-person">Ольга</div>
                        </div>
                        <div class="selling__example-item-desc">Подскажите, пожалуйста, как можно подать на алименты отца ребенка, если не состоим в браке и если он не хочет добровольно помогать материально? А в суде отказываются принимать заявление.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="selling__example-item">
                        <div class="selling__example-item-heading">
                            <div class="selling__example-item-location">Москва (Московская область)</div>
                            <a href="tel:+79096573723" class="selling__example-item-phone">+7-909-657-37-23</a>
                            <div class="selling__example-item-person">Ольга</div>
                        </div>
                        <div class="selling__example-item-desc">Подскажите, пожалуйста, как можно подать на алименты отца ребенка, если не состоим в браке и если он не хочет добровольно помогать материально? А в суде отказываются принимать заявление.</div>
                    </div>
                </div>
                <div class="w-100"></div>
                <div class="col-md-6">
                    <div class="selling__example-item">
                        <div class="selling__example-item-heading">
                            <div class="selling__example-item-location">Москва (Московская область)</div>
                            <a href="tel:+79096573723" class="selling__example-item-phone">+7-909-657-37-23</a>
                            <div class="selling__example-item-person">Ольга</div>
                        </div>
                        <div class="selling__example-item-desc">Подскажите, пожалуйста, как можно подать на алименты отца ребенка, если не состоим в браке и если он не хочет добровольно помогать материально? А в суде отказываются принимать заявление.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="selling__example-item">
                        <div class="selling__example-item-heading">
                            <div class="selling__example-item-location">Москва (Московская область)</div>
                            <a href="tel:+79096573723" class="selling__example-item-phone">+7-909-657-37-23</a>
                            <div class="selling__example-item-person">Ольга</div>
                        </div>
                        <div class="selling__example-item-desc">Подскажите, пожалуйста, как можно подать на алименты отца ребенка, если не состоим в браке и если он не хочет добровольно помогать материально? А в суде отказываются принимать заявление.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="selling__reject">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-lg-1">
                    <div class="selling__reject-desc">Все поступающие к нам юридические лиды проходят вручную фильтруются от спамных вопросов, но если вам все же пришла неликвидная заявка, <span class="selling__reject-desc--bold">вы можете отправить ее в брак</span>, если она соответствует одной из следующих причин:</div>
                    <ul class="selling__reject-list">
                        <li class="selling__reject-item">
                            <div class="selling__reject-item-title">Не тот регион</div>
                            <div class="selling__reject-item-desc">Проблема у клиента в другом регионе. Клиент находится в другом регионе, поэтому работать с ним не представляется возможным.</div>
                        </li>
                        <li class="selling__reject-item">
                            <div class="selling__reject-item-title">Неверный номер</div>
                            <div class="selling__reject-item-desc">Лид содержит несуществующий или некорректный номер телефона.</div>
                        </li>
                        </li>
                        <li class="selling__reject-item">
                            <div class="selling__reject-item-title">Не юридический вопрос</div>
                            <div class="selling__reject-item-desc">Если вопрос точно адресован не юристу. <div>Например: «Как пройти в библиотеку?» </div></div>
                        </li>
                        <li class="selling__reject-item">
                            <div class="selling__reject-item-title">Спам / тестовая заявка</div>
                            <div class="selling__reject-item-desc">Сообщение рекламного характера или тестовая заявка.</div>
                        </li>
                    </ul>
                </div>
            </div>
    </section>

    <section class="selling__popular">
        <div class="container">
            <h2 class="selling__popular-title section-title">Популярные тематики лидов</h2>
            <div class="row">
                <div class="col-12 col-lg-8 offset-lg-1">
                    <div class="selling__popular-desc">Тематики лидов самые разные, все они актуальны и платежеспособны. Но самые популярные из них обеспечивает стабильную рентабельность, благодаря хорошим договорам.</div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-6 col-md-2">
                    <div class="selling__popular-item">
                        <div class="selling__popular-img img">
                            <img src="/img/selling-popular-img-1.png" alt="">
                        </div>
                        <div class="selling__popular-item-title">Земельное право</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="selling__popular-item">
                        <div class="selling__popular-img img">
                            <img src="/img/selling-popular-img-2.png" alt="">
                        </div>
                        <div class="selling__popular-item-title">Жилищные вопросы</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="selling__popular-item">
                        <div class="selling__popular-img img">
                            <img src="/img/selling-popular-img-3.png" alt="">
                        </div>
                        <div class="selling__popular-item-title">Кредитные проблемы</div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="selling__popular-item">
                        <div class="selling__popular-img img">
                            <img src="/img/selling-popular-img-4.png" alt="">
                        </div>
                        <div class="selling__popular-item-title">Трудовое право</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="selling__about">
        <div class="container">
            <h2 class="selling__about-title section-title">Где и как мы собираем юридические заявки</h2>
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="selling__about-desc">
                        <p>Помимо своих нескольких ресурсов мы работаем с нашими партнерами вебмастерами и владельцами сайтов юридической направленности и получаем значительную часть лидов от них. Мы на этом рынке уже несколько лет, и за это время заработали себе репутацию надежных партнеров.</p>
                    </div>
                    <div class="selling__about-wrap">
                        <div class="selling__about-left">
                            <div class="selling__about-item">Собственые дополнительные сайты</div>
                            <div class="selling__about-item">Входящие звонки на горячую линию</div>
                        </div>
                        <div class="selling__about-center img">
                            <img src="/img/selling-about-logo.png" alt="">
                        </div>
                        <div class="selling__about-right">
                            <div class="selling__about-item">Письменные обращения</div>
                            <div class="selling__about-item">Сайты-партнеры</div>
                        </div>
                    </div>
                    <div class="selling__about-desc">
                        <p>Помимо своих нескольких ресурсов мы работаем с нашими партнерами вебмастерами и владельцами сайтов юридической направленности и получаем значительную часть лидов от них. Мы на этом рынке уже несколько лет, и за это время заработали себе репутацию надежных партнеров.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="selling__process">
        <div class="container">
            <h2 class="selling__process-title section-title">Процесс работы</h2>
            <div class="row justify-content-center">
                <div class="col-sm-5 col-lg-3">
                    <div class="steps__item">
                        <div class="steps__item-top">
                            <div class="steps__item-img">
                                <img src="/img/selling-process-item-1.png" alt="">
                            </div>
                            <div class="steps__item-value">шаг 1</div>
                        </div>
                        <div class="steps__item-bottom">
                            <div class="steps__item-title">Регистрируетесь</div>
                            <div class="arrow_box"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 col-lg-3">
                    <div class="steps__item">
                        <div class="steps__item-top">
                            <div class="steps__item-img">
                                <img src="/img/selling-process-item-2.png" alt="">
                            </div>
                            <div class="steps__item-value">шаг 2</div>
                        </div>
                        <div class="steps__item-bottom">
                            <div class="steps__item-title">Создаете кампанию на город или регион</div>
                            <div class="arrow_box"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 col-lg-3">
                    <div class="steps__item">
                        <div class="steps__item-top">
                            <div class="steps__item-img">
                                <img src="/img/selling-process-item-3.png" alt="">
                            </div>
                            <div class="steps__item-value">шаг 3</div>
                        </div>
                        <div class="steps__item-bottom">
                            <div class="steps__item-title">Пополняете баланс</div>
                            <div class="arrow_box"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5 col-lg-3">
                    <div class="steps__item">
                        <div class="steps__item-top">
                            <div class="steps__item-img">
                                <img src="/img/selling-process-item-4.png" alt="">
                            </div>
                            <div class="steps__item-value">шаг 4</div>
                        </div>
                        <div class="steps__item-bottom">
                            <div class="steps__item-title">Получаете лиды</div>
                            <div class="arrow_box"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="selling__benefits">
        <div class="container">
            <h2 class="selling__benefits-title section-title">Выгода работы с лидами</h2>
            <div class="row">
                <div class="col-lg-4">
                    <div class="selling__benefits-list">
                        <div class="selling__benefits-item">
                            <div class="selling__benefits-item-desc">Количество купленных заявок</div>
                            <div class="selling__benefits-item-value">100</div>
                        </div>
                        <div class="selling__benefits-item">
                            <div class="selling__benefits-item-desc">Стоимость одной заявки</div>
                            <div class="selling__benefits-item-value">100,00 руб.</div>
                        </div>
                        <div class="selling__benefits-item">
                            <div class="selling__benefits-item-desc">Общая стоимость партии лидов</div>
                            <div class="selling__benefits-item-value">10 000,00 руб.</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="selling__benefits-list">
                        <div class="selling__benefits-item">
                            <div class="selling__benefits-item-desc">Конверсия из лида в договор</div>
                            <div class="selling__benefits-item-value">15%</div>
                        </div>
                        <div class="selling__benefits-item">
                            <div class="selling__benefits-item-desc">Количество договоров в партии</div>
                            <div class="selling__benefits-item-value">15</div>
                        </div>
                        <div class="selling__benefits-item">
                            <div class="selling__benefits-item-desc">Средняя цена заключенного договора</div>
                            <div class="selling__benefits-item-value">10 000,00 руб.</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="selling__benefits-list">
                        <div class="selling__benefits-item">
                            <div class="selling__benefits-item-desc">Сумма заключенных договоров</div>
                            <div class="selling__benefits-item-value">150 000,00 руб.</div>
                        </div>
                        <div class="selling__benefits-item selling__benefits-item--colored">
                            <div class="selling__benefits-item-desc">Прибыль с партии лидов</div>
                            <div class="selling__benefits-item-value">140 000,00 руб.</div>
                        </div>
                        <div class="selling__benefits-item selling__benefits-item--colored">
                            <div class="selling__benefits-item-desc">% рентабельности</div>
                            <div class="selling__benefits-item-value">1400,00%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-9 offset-lg-1">
                    <div class="selling__benefits-desc ">
                        <p>Вам больше не нужно будет тратить время и деньги на оплату штатных сотрудников. </p>
                        <p class="selling__benefits-desc--bold">Вы будете просто покупать готовые заявки и работать с ними.</p>
                    </div>
                    <div class="selling__benefits-desc ">
                        <p>А еще вы получите возможность использовать CRM для юридической деятельности <span class="selling__benefits-desc--free">Бесплатно!</span></p>
                    </div>
                    <div class="selling__benefits-desc selling__benefits-desc--bordered">
                        <p>Благодаря нашим партнерам мы имеем уникальную возможность предоставить нашим покупателям лидов crm-систему, специально разработанную для юридического бизнеса. После регистрации тут, вы автоматически получите аккаунт в yurcrm.ru, с бесплатным тарифом! И лиды которые вы приобретаете у нас, автоматически будут отправляться прямо в ваш личный кабинет в CRM!</p>
                    </div>
                    <?php echo CHtml::link('Зарегистрироваться и начать', Yii::app()->createUrl('user/create',
                        ['role' => User::ROLE_BUYER]), ['class' => 'main-btn selling__benefits-btn']); ?>

                </div>
            </div>
        </div>
    </section>
</main>













