<?php

use App\helpers\DateHelper;

$purifier = new CHtmlPurifier();
?>



<!-- Reviewes -->
<section class="reviewes">
    <div class="container">
        <h2 class="reviewes__title section-title">Отзывы</h2>
        <div class="reviewes__gallery">
            <!-- Swiper -->
            <div class="reviewes-swiper-container">
                <div class="swiper-wrapper">
                    <?php if (count($testimonials) > 0): ?>
                    <?php foreach ($testimonials as $index => $testimonial): ?>
                    <div class="swiper-slide">
                        <div class="reviewes__item">
                            <div class="reviewes__item-img img">
                                <img src="img/reviewes-item-img.png" alt="">
                            </div>
                            <div class="reviewes__item-heading">
                                <div class="reviewes__item-author"><?= CHtml::encode($testimonial->author->name) ?></div>
                                <div class="reviewes__item-date"><?= DateHelper::niceDate($testimonial->dateTime, false, false); ?></div>
                            </div>
                            <div class="reviewes__item-desc"><?= $purifier->purify($testimonial->text) ?></div>
                            <?php if ($testimonial->question): ?>
                            <div class="reviewes__item-footer">
                                <div class="reviewes__item-footer-title">Вопрос:</div>
                                <a href class="reviewes__item-footer-value">
                                    <?= CHtml::link(CHtml::encode($testimonial->question->title), Yii::app()->createUrl('question/view',
                                        ['id' => $testimonial->question->id]), ['class' => 'reviewes__item-footer-value']); ?></a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Add Arrows -->
            <div class="reviewes-button-next"></div>
            <div class="reviewes-button-prev"></div>
            <!-- Add Pagination -->
            <div class="reviewes-pagination"></div>
        </div>
        <a href="" class="reviewes__btn">Задать вопрос онлайн</a>
    </div>
</section>





