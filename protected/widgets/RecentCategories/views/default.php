<?php
/** @var QuestionCategory $recentCategory */

use App\models\QuestionCategory;

if (empty($recentCategories) || 0 == sizeof($recentCategories)) {
    echo 'Не найдено ни одной категории';
}
?>




<!-- Materials -->
<section class="materials">
    <div class="container">
        <h2 class="materials__title section-title">Новые правовые материалы</h2>
        <div class="materials__gallery">
            <!-- Swiper -->
            <div class="materials-swiper-container">
                <div class="swiper-wrapper">
                    <?php foreach ($recentCategories as $recentCategory): ?>
                    <div class="swiper-slide">
                        <div class="materials__item">
                            <div class="materials__item-img img">
                                <img src="<?= $recentCategory->getImagePath(); ?>" alt="<?= CHtml::encode($recentCategory->seoH1); ?>">
                            </div>
                            <div class="materials__item-content">
                                <h3 class="materials__item-title"><?= CHtml::encode($recentCategory->seoH1); ?></h3>
                                <div class="materials__item-desc"><?= \App\helpers\StringHelper::cutString($recentCategory->seoDescription, 70) ?> </div>
                                <a href="<?= Yii::app()->createUrl('questionCategory/alias', $recentCategory->getUrl()) ?>" class="materials__item-more">Читать подробнее</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Add Arrows -->
            <div class="materials-button-next"></div>
            <div class="materials-button-prev"></div>
            <!-- Add Pagination -->
            <div class="materials-pagination"></div>
        </div>
        <div class="row justify-content-center materials-items-wrap">
            <?php foreach ($recentCategories as $recentCategory): ?>

            <div class="col-md-4">
                <div class="materials__item">
                    <div class="materials__item-img img">
                        <img src="<?= $recentCategory->getImagePath(); ?>" alt="<?= CHtml::encode($recentCategory->seoH1); ?>">
                    </div>
                    <div class="materials__item-content">
                        <h3 class="materials__item-title"><?= CHtml::encode($recentCategory->seoH1); ?></h3>
                        <div class="materials__item-desc"><?= \App\helpers\StringHelper::cutString($recentCategory->seoDescription, 70); ?> </div>
                        <a href="<?= Yii::app()->createUrl('questionCategory/alias', $recentCategory->getUrl()) ?>" class="materials__item-more">Читать подробнее</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <a href="<?= Yii::app()->createUrl('/cat/') ?>" class="materials__btn">Все правовые материалы</a>
    </div>
</section>
