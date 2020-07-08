<?php use App\helpers\DateHelper;
use App\helpers\StringHelper;
use App\models\User;


?>
    <!-- Consultations -->
    <section class="consultations">
        <div class="container">
            <h2 class="consultations__title section-title">Последние консультации</h2>
            <div class="consultations__gallery">
                <!-- Swiper -->
                <div class="consultations-swiper-container">
                    <div class="swiper-wrapper">

                    <?php

                    if (empty($questions) || 0 == count($questions)) {
                            echo 'Не найдено ни одного ответа';
                    }
                    ?>
                    <?php foreach ($questions as $question):
//                        CVarDumper::dump($questions,5,true);die;
                        ?>
                        <div class="swiper-slide">
                            <div class="consultations__item">
                                <div class="consultations__date"><?= date('d.m.Y', strtotime($question->createDate)) ?></div>
                                <a href="" class="consultations__category"><?= $question->category ?></a>
                                <div class="consultations__item-title"><?= $question->title ?></div>
                                <div class="consultations__item-desc"><?= $question->questionText ?></div>
                                <a href="" class="consultations__item-btn">
                                    <span class="consultations__item-btn-title"><?= count($question->answers) ?> ответа</span>
                                    <span class="consultations__item-btn-img img">
			      				<img src="img/consultations-item-btn-img.png" alt="">
			      			</span>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    </div>
                </div>
                <!-- Add Arrows -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
            <a href="" class="consultations__btn">Задать вопрос онлайн</a>
        </div>
    </section>



