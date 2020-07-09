<?php

use App\helpers\DateHelper;
use App\helpers\NumbersHelper;
use App\helpers\StringHelper;


foreach ($questions as $question): ?>
    <div class="swiper-slide">
        <div class="consultations__item">
            <?php if ($question->price) : ?>
            <div class="question-paid-img img">
                <img src="img/question-paid-img.png" alt="">
            </div>
            <?php endif; ?>
            <div class="consultations__date">
                <?php
                $questionDatetime = (new DateTime($question->createDate));
                $nowDate = (new DateTime(date('Y-m-d 00:00:00')));
                if ($questionDatetime >= $nowDate) {
                    echo 'сегодня в ' . $questionDatetime->format('H:i');
                } else {
                    echo DateHelper::niceDate($question->createDate, false, false);
                }
                ?>
            </div>
            <a href="<?= Yii::app()->createUrl('/cat/' . $question->categoryAlias) ?>" class="consultations__category"><?= CHtml::encode($question->category) ?></a>
            <div class="consultations__item-title"> <?= CHtml::encode($question->title) ?></div>
            <div class="consultations__item-desc"><?= StringHelper::cutString(CHtml::encode($question->questionText), 70) ?></div>
            <a href="<?= Yii::app()->createUrl('/q/' . $question->id) ?>" class="consultations__item-btn">
                <span class="consultations__item-btn-title"><?= count($question->answers) ?> ответ</span>
                <span class="consultations__item-btn-img img">
					      				<img src="img/consultations-item-btn-img.png" alt="">
					      			</span>
            </a>
        </div>
    </div>

<?php endforeach; ?>

