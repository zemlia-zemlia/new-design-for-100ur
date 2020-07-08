<?php

use App\models\Post;

if (empty($recentPosts) || !count($recentPosts)) {
    echo 'Не найдено ни одного поста';
}
$purifier = new CHtmlPurifier();
?>





<!-- News -->
<section class="news">
    <div class="container">
        <h2 class="news__title section-title">Новости и статьи</h2>
        <div class="news__gallery">
            <!-- Swiper -->
            <div class="news-swiper-container">
                <div class="swiper-wrapper">

                    <?php foreach ($recentPosts as $index => $recentPost): ?>

                    <div class="swiper-slide">
                        <div class="news__item">
                            <div class="news__item-img img">
                                <?php if ($recentPost['photo']): ?>
                                <?php
                                $postObject = new Post();
                                $postObject->attributes = $recentPost;
                                ?>
                                <img src="<?= $postObject->getPhotoUrl('thumb') ?>" alt="<?= CHtml::encode($recentPost['title']) ?>">
                                <?php endif; ?>
                            </div>
                            <div class="news__item-content">
                                <div class="news__item-heading">
                                    <div class="news__item-date">10.01.2019</div>
                                    <div class="news__item-views">
                                        <div class="news__item-views-title">Просмотров:</div>
                                        <div class="news__item-views-value">108</div>
                                    </div>
                                    <div class="news__item-comments">
                                        <div class="news__item-comments-title">Комментариев:</div>
                                        <div class="news__item-comments-value">8</div>
                                    </div>
                                </div>
                                <h3 class="news__item-title"><?  CHtml::encode($recentPost['title']) ?> </h3>
                                <div class="news__item-desc">Отвечая на вопросы журналистов в процессе пресс-конференции в Осаке. Путин заявил, что Россия не будет проявлять инициативы для снятия санкций со стороны Евросоюза и США. Лидер сказал...</div>
                                <a href="<?= Yii::app()->createUrl('post/view', ['id' => $recentPost['id'], 'alias' => $recentPost['alias']]) ?>" class="news__item-more">Читать подробнее</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Add Arrows -->
            <div class="news-button-next"></div>
            <div class="news-button-prev"></div>
            <!-- Add Pagination -->
            <div class="news-pagination"></div>
        </div>
        <div class="row justify-content-center news__item-wrap">

            <?php foreach ($recentPosts as $index => $recentPost): ?>
            <div class="col-md-4">
                <div class="news__item">
                    <div class="news__item-img img">
                        <?php if ($recentPost['photo']): ?>
                            <?php
                            $postObject = new Post();
                            $postObject->attributes = $recentPost;
                            ?>
                            <img src="<?= $postObject->getPhotoUrl('thumb') ?>" alt="<?= CHtml::encode($recentPost['title']) ?>">
                        <?php endif; ?>
                    </div>
                    <div class="news__item-content">
                        <div class="news__item-heading">
                            <div class="news__item-date">10.01.2019</div>
                            <div class="news__item-views">
                                <div class="news__item-views-title">Просмотров:</div>
                                <div class="news__item-views-value">108</div>
                            </div>
                            <div class="news__item-comments">
                                <div class="news__item-comments-title">Комментариев:</div>
                                <div class="news__item-comments-value">8</div>
                            </div>
                        </div>
                        <h3 class="news__item-title"><?  CHtml::encode($recentPost['title']) ?>  </h3>
                        <div class="news__item-desc">Отвечая на вопросы журналистов в процессе пресс-конференции в Осаке. Путин заявил, что Россия не будет проявлять инициативы для снятия санкций со стороны Евросоюза и США. Лидер сказал...</div>
                        <a href="" class="news__item-more">Читать подробнее</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <a href="" class="news__btn">Все новости и статьи</a>
    </div>
</section>