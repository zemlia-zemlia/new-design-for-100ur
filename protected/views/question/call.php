<?php
$this->setPageTitle('Консультация по телефону ' . Yii::app()->name);
Yii::app()->clientScript->registerMetaTag('Заказать звонок юриста, получить консультацию по телефону', 'description');
?>


	<!-- Activity -->
<section class="activity question-activity">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-3">
                <div class="activity__item">
                    <div class="activity__item-value">76 518</div>
                    <div class="activity__item-desc">Вопросов задано</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="activity__item">
                    <div class="activity__item-value">128 856</div>
                    <div class="activity__item-desc">Ответов получено</div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="activity__item">
                    <div class="activity__item-value">1 844</div>
                    <div class="activity__item-desc">Юристов на сайте</div>
                </div>
            </div>
        </div>
    </div>
</section>




<!-- Steps -->
<section class="steps question-steps call-back-steps">
    <div class="container">
        <div class="row justify-content-center align-items-stretch">
            <div class="col-sm-8 col-lg-5">
                <h2 class="main__title call-back-steps__title">Как это работает</h2>
                <div class="steps__item">
                    <div class="steps__item-top">
                        <div class="steps__item-img">
                            <img src="/img/steps-item-img-4.png" alt="">
                        </div>
                        <div class="steps__item-value">шаг 1</div>
                    </div>
                    <div class="steps__item-bottom">
                        <div class="steps__item-title">Закажите обратный звонок</div>
                        <div class="steps__item-desc">Мы ежедневно получаем более тысячи запросов на консультацию по телефону, укажите контактные данные и профильный юрист свяжется с вами в ближайшее время.</div>
                        <div class="arrow_box"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8 col-lg-5">
                <div class="steps__item">
                    <div class="steps__item-top">
                        <div class="steps__item-img img">
                            <img src="/img/steps-item-img-5.png" alt="">
                        </div>
                        <div class="steps__item-value">шаг 2</div>
                    </div>
                    <div class="steps__item-bottom">
                        <div class="steps__item-title">Получите консультацию по телефону</div>
                        <div class="steps__item-desc">Получив консультацию вы получите ответ на свой вопрос и будете сориентированы как вам действовать дальше в вашей ситуации.</div>
                        <div class="arrow_box"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<main class="main">
    <div class="container">
        <h2 class="main__title">Закажите обратный звонок</h2>
        <div class="row justify-content-between">
            <div class="col-md-6 col-lg-7">
                <form action="" class="question-online__form">
                    <div class="form-input-wrap">
                        <label for="" class="form-input-label">Ваше имя</label>
                        <div class="form-input">
                            <input type="text" name="name" placeholder="Как вас зовут?">
                        </div>
                    </div>
                    <div class="form-input-wrap">
                        <label for="" class="form-input-label">Ваш номер телефона</label>
                        <div class="form-input">
                            <input type="tel" name="phone" id="phone" placeholder="+7 (___) ___-__-__">
                        </div>
                    </div>
                    <div class="form-input-wrap">
                        <label for="" class="form-input-label">Ваш город</label>
                        <div class="form-input-select">
                            <select name="" id="">
                                <option value="">Красноярск</option>
                                <option value="">Красноярск</option>
                                <option value="">Красноярск</option>
                                <option value="">Красноярск</option>
                            </select>
                        </div>
                    </div>
                    <a href="#ex3" rel="modal:open" class="question-online__btn main-btn">Задать вопрос</a>
                    <div class="question-online-check-wrap">
                        <input id="question-online-check" type="checkbox" name="question-online-check" value="check1" class="checkbox">
                        <label for="question-online-check">Отправляя вопрос, вы соглашаетесь с условиями <a href="" class="question-online__policy">Пользовательского соглашения</a></label>
                    </div>
                </form>
            </div>
            <div class=" col-md-5 col-lg-4">
                <div class="last-consultation">
                    <div class="last-consultation__title">Последние консультации</div>
                    <div class="consultations__item">
                        <div class="consultations__date">10.01.2019</div>
                        <a href="" class="consultations__category">Категория права</a>
                        <div class="consultations__item-title">Нужно ли будет платить налог, если я хочу сдать свою старую машину по программе Trade-in?</div>
                        <div class="consultations__item-desc">Здравствуйте, автомобиль был куплен в августе 2019 года новый за 1 млн 250 тыс. Хочу сдать...</div>
                        <a href="" class="consultations__item-btn">
                            <span class="consultations__item-btn-title">2 ответа</span>
                            <span class="consultations__item-btn-img img">
		      				<img src="../img/consultations-item-btn-img.png" alt="">
		      			</span>
                        </a>
                    </div>
                    <div class="consultations__item">
                        <div class="consultations__date">10.01.2019</div>
                        <a href="" class="consultations__category">Категория права</a>
                        <div class="consultations__item-title">Нужно ли будет платить налог, если я хочу сдать свою старую машину по программе Trade-in?</div>
                        <div class="consultations__item-desc">Здравствуйте, автомобиль был куплен в августе 2019 года новый за 1 млн 250 тыс. Хочу сдать...</div>
                        <a href="" class="consultations__item-btn">
                            <span class="consultations__item-btn-title">2 ответа</span>
                            <span class="consultations__item-btn-img img">
		      				<img src="../img/consultations-item-btn-img.png" alt="">
		      			</span>
                        </a>
                    </div>
                    <div class="consultations__item">
                        <div class="consultations__date">10.01.2019</div>
                        <a href="" class="consultations__category">Категория права</a>
                        <div class="consultations__item-title">Нужно ли будет платить налог, если я хочу сдать свою старую машину по программе Trade-in?</div>
                        <div class="consultations__item-desc">Здравствуйте, автомобиль был куплен в августе 2019 года новый за 1 млн 250 тыс. Хочу сдать...</div>
                        <a href="" class="consultations__item-btn">
                            <span class="consultations__item-btn-title">2 ответа</span>
                            <span class="consultations__item-btn-img img">
		      				<img src="../img/consultations-item-btn-img.png" alt="">
		      			</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<div class='flat-panel'>
    <div class='inside'>
        <?php echo $this->renderPartial('_formCall', [
            'model' => $model,
            'townsArray' => $townsArray,
            'allDirections' => $allDirections,
        ]); ?>
    </div>
</div>
