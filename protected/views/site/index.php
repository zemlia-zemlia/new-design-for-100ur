<?php
$this->setPageTitle('Юридическая консультация онлайн - бесплатная помощь юристов и адвокатов круглосуточно');
Yii::app()->clientScript->registerMetaTag('100 Юристов - круглосуточные бесплатные юридические консультации онлайн.  Вы можете задать любой вопрос юристу или самостоятельно найти ответ в нашей правовой базе.', 'description');
Yii::app()->clientScript->registerMetaTag('бесплатная юридическая консультация онлайн', 'keywords');
Yii::app()->clientScript->registerLinkTag('canonical', null, 'https://' . $_SERVER['SERVER_NAME']);
?>



<section class="steps">
    <div class="container">
        <h2 class="steps__title section-title">Как это работает?</h2>
        <div class="row justify-content-center align-items-stretch">
            <div class="col-sm-4 col-lg-3">
                <div class="steps__item">
                    <div class="steps__item-top">
                        <div class="steps__item-img">
                            <img src="img/steps-item-img-1.png" alt="">
                        </div>
                        <div class="steps__item-value">шаг 1</div>
                    </div>
                    <div class="steps__item-bottom">
                        <div class="steps__item-title">Задайте вопрос</div>
                        <div class="steps__item-desc">Мы получаем более 1500 вопросов каждый день. Задайте свой!</div>
                        <div class="arrow_box"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-lg-3">
                <div class="steps__item">
                    <div class="steps__item-top">
                        <div class="steps__item-img img">
                            <img src="img/steps-item-img-2.png" alt="">
                        </div>
                        <div class="steps__item-value">шаг 2</div>
                    </div>
                    <div class="steps__item-bottom">
                        <div class="steps__item-title">Получите ответы</div>
                        <div class="steps__item-desc">На вопросы круглосуточно отвечают юристы со всей России. Среднее время ответа - 15 минут.</div>
                        <div class="arrow_box"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-lg-3">
                <div class="steps__item">
                    <div class="steps__item-top">
                        <div class="steps__item-img img">
                            <img src="img/steps-item-img-3.png" alt="">
                        </div>
                        <div class="steps__item-value">шаг 3</div>
                    </div>
                    <div class="steps__item-bottom">
                        <div class="steps__item-title">Проблема решена</div>
                        <div class="steps__item-desc">95,4% клиентов остаются полностью довольны ответами и рекомендуют нас друзьям.</div>
                        <div class="arrow_box"></div>
                    </div>
                </div>
            </div>
            <div class="w-100 steps__separator">Или</div>
            <div class="col-sm-4 col-lg-3">
                <div class="steps__item">
                    <div class="steps__item-top">
                        <div class="steps__item-img img">
                            <img src="img/steps-item-img-4.png" alt="">
                        </div>
                        <div class="steps__item-value">шаг 1</div>
                    </div>
                    <div class="steps__item-bottom">
                        <div class="steps__item-title">Закажите обратный звонок</div>
                        <div class="steps__item-desc">Мы получаем более 1500 вопросов каждый день. Задайте свой!</div>
                        <div class="arrow_box"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-lg-3">
                <div class="steps__item">
                    <div class="steps__item-top">
                        <div class="steps__item-img img">
                            <img src="img/steps-item-img-5.png" alt="">
                        </div>
                        <div class="steps__item-value">шаг 2</div>
                    </div>
                    <div class="steps__item-bottom">
                        <div class="steps__item-title">Получите консультацию по телефону</div>
                        <div class="steps__item-desc">На вопросы круглосуточно отвечают юристы со всей России. Среднее время ответа - 15 минут.</div>
                        <div class="arrow_box"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



        <?php
        // выводим виджет с деревом категорий
        $this->widget('application.widgets.CategoriesTree.CategoriesTree', [
            'template' => 'columns',
        ]);
        ?>




            <?php
            // выводим виджет с последними вопросами
            $this->widget('application.widgets.RecentAnswers.RecentAnswers', [
                'template' => 'default',
                'limit' => 4,
                'cacheTime' => 3600,
            ]);
            ?>

<!-- Benefits -->
<section class="benefits">
    <div class="benefits__bg">
        <img src="img/benefits-bg.png" alt="">
    </div>
    <div class="container">
        <div class="benefits__item">
            <div class="benefits__item-img img">
                <img src="img/benefits-item-img-1.png" alt="">
            </div>
            <div class="benefits__item-desc">100% гарантия качества</div>
        </div>
        <div class="benefits__item">
            <div class="benefits__item-img img">
                <img src="img/benefits-item-img-2.png" alt="">
            </div>
            <div class="benefits__item-desc">Политика защиты клиентов </div>
        </div>
    </div>
</section>



<?php
// выводим виджет с последними отзывами
$this->widget('application.widgets.Testimonials.TestimonialsWidget', [
    'template' => 'default',
    'limit' => 6,
    'cacheTime' => 3600,
]);
?>


<section class="questions">
    <div class="container">
        <div id="questionsContainer">
            <ul class="questions__list">
                <li class="questions__list-item">
                    <a href="#tab1" class="questions__list-link">Бесплатные вопросы</a>
                </li>
                <li class="questions__list-item">
                    <a href="#tab2" class="questions__list-link">Платные вопросы</a>
                </li>
            </ul>

            <div id="tab1">
                <div class="question-free__gallery">
                    <!-- Swiper -->
                    <div class="question-free-swiper-container">
                        <div class="swiper-wrapper">


                            <?php
                            $this->widget('application.widgets.PopularQuestions.PopularQuestions', [
                                'template' => 'default',
                                'cacheTime' => 10,
                            ]);
                            ?>
                        </div>
                    </div>
                    <!-- Add Arrows -->
                    <div class="question-free-button-next"></div>
                    <div class="question-free-button-prev"></div>
                    <!-- Add Pagination -->
                    <div class="question-free-pagination"></div>
                </div>
                <a href="" class="question__btn">Показать все вопросы</a>
            </div>

            <div id="tab2">
                <div class="question-paid__gallery">
                    <!-- Swiper -->
                    <div class="question-paid-swiper-container">
                        <div class="swiper-wrapper">
                            <?php
                            $this->widget('application.widgets.PopularQuestions.PopularQuestions', [
                                'template' => 'default',
                                'cacheTime' => 10,
                                'showPayed' => true

                            ]);
                            ?>
                        </div>
                    </div>
                    <!-- Add Arrows -->
                    <div class="question-paid-button-next"></div>
                    <div class="question-paid-button-prev"></div>
                    <!-- Add Pagination -->
                    <div class="question-paid-pagination"></div>
                </div>
                <a href="" class="question__btn">Показать все вопросы</a>
            </div>
        </div>
    </div>
</section>





            <?php
            $this->widget('application.widgets.RecentPosts.RecentPosts', [
                'number' => 3,
                'order' => 'fresh_views',
                'intervalDays' => 1160,
                'template' => 'horizontal',
            ]);
            ?>




            <?php
            $this->widget('application.widgets.RecentCategories.RecentCategories', [
                'number' => 3,
                'template' => 'default',
                'columns' => 3,
            ]);
            ?>


<section class="about">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-md-7">
                <h2 class="about__title section-title">О проекте</h2>
                <div class="about__item">
                    <h3 class="about__item-title">Бесплатная юридическая консультация</h3>
                    <div class="about__item-desc">
                        <p>Наш интернет-портал, сотрудничая с юристами и адвокатами высокого профессионального уровня, предоставляет возможность получить профессиональную бесплатную юридическую консультацию онлайн, а также заказать нужный документ или найти юриста из вашего города или региона для представления интересов в судах и организациях. </p>
                        <p>Получить бесплатную юридическую консультацию онлайн могут как жители соквы и СПБ, так и других регионов России и СНГ. </p>
                        <p>На все вопросы отвечают специалисты, которые прошли проверку наличия профильного образования и знаний сотрудниками нашего портала. </p>
                        <p>Консультации от профессионалов - вы можете не только поинтересоваться тем, как обстоят ваши дела в отношении спорной ситуации, а также в случае судебного процесса понять, если ли у вас шансы выиграть дело, в котором вы участвуете не только как истец, но и как ответчик. Вы можете получить настоящую бесплатную эффективную юридическую помощь и поддержку, ведь наши специалисты имеют огромный опыт взаимодействия в вопросах самых различных сфер и направлений.</p>
                    </div>
                </div>
                <div class="about__item">
                    <h3 class="about__item-title">Онлайн-консультация юриста</h3>
                    <div class="about__item-desc">
                        <p>Помощь специалиста в суде или его консультация может быть предоставлена в самых различных сферах правовой практики. Заметьте, что данная услуга оказывается бесплатно. Вам дается возможность получить юридическую консультацию по всем вопросам, с которыми вы можете столкнуться на работе, дома, на даче и т.д. Юрист нам требуется не менее часто, чем, например, врач, ведь наши с вами права могут нарушаться где угодно: от незаконного сбора членских взносов в дачном кооперативе до залива квартиры соседями или обмана в магазине.</p>
                        <p>При каждом таком случае необходимо понимать, как поступить правильно, чтобы не усугубить ситуацию или ее разрешить. В этом вам помогут адвокаты нашего портала. И для этого не обязательно регистрироваться или оставлять свой номер телефона.  Все можно сделать в режиме онлайн.</p>
                        <p>Стоит отметить, что бесплатная юридическая консультация онлайн, во время которой можно задать вопросы, проводится в любое удобное для вас время. Это значит, что при необходимости вы можете получить нужную информацию даже в ночное время.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="about__widget">

                </div>
            </div>
        </div>
    </div>
</section>