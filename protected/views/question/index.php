<?php
/* @var $this QuestionController */

use App\helpers\DateHelper;
use App\helpers\NumbersHelper;
use App\helpers\StringHelper;

/* @var $dataProvider CActiveDataProvider */

Yii::app()->clientScript->registerLinkTag('canonical', null, Yii::app()->createUrl('q'));

$pageTitle = 'Последние советы юристов бесплатно онлайн ';

$this->setPageTitle($pageTitle);

Yii::app()->clientScript->registerMetaTag('Советы юристов по всем отраслям права задайте свой вопрос и получите ответ в течении 15 минут', 'description');
?>


<main class="main archive">
    <div class="container">
        <h2 class="archive__title main__title">Последние вопросы юристам</h2>
        <div class="row justify-content-between">
            <div class="col-md-7 col-lg-8">
                <div class="archive-questions">
                    <div class="archive-questions__body">
                        <?php foreach ($questions as $question): ?>

                        <div class="archive-questions__item">
                            <?= CHtml::link(StringHelper::mb_ucfirst($question->title, 'utf-8'),
                                Yii::app()->createUrl('question/view', ['id' => $question->id]), ['class' => 'archive-questions__title']); ?>
                            <?php if ($question->answersCount > 0) : ?>
                            <a href=""	class="archive-questions__btn"><?= $question->answersCount ?> ответ</a>
                            <?php else: ?>
                            <div class="archive-questions__no-answer">Нет ответа</div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                        <a href="" class="archive-questions__more-btn">Показать еще 25 вопросов</a>


                    </div>

                    <?php $monthsNames = DateHelper::getMonthsNames(); ?>
                    <?php foreach ($datesArray as $year => $months): ?>

                        <h4><?= $year; ?></h4>
                    <ul class="archive__list">

                        <div class="archive__list-wrap">

                            <?php foreach ($months as $month): ?>
                            <li class="archive__list-item">
                                <?= CHtml::link($monthsNames[$month], Yii::app()->createUrl('question/archive', ['date' => $year . '-' . $month]),
                                    ['class' => (Yii::app()->request->requestUri ==  Yii::app()->createUrl('question/archive', ['date' => $year . '-' . $month])) ?
                                        'archive__list-link archive__list-link--active' : 'archive__list-link archive__list-link']) ?>
                            </li>


                            <?php endforeach; ?>
                        </div>


                    </ul>


                    <?php endforeach; ?>
                </div>
            </div>
            <div class=" col-md-5 col-lg-4">
                <div class="archive__aside">
                    <form action="" method="" name="" class="advice-form">
                        <h3 class="advice-form__title">Получите совет от юриста онлайн</h3>
                        <div class="advice-form__textarea">
                            <textarea name="" id="" placeholder="Опишите вашу проблему..."></textarea>
                        </div>
                        <div class="advice-form__input">
                            <input type="text" name="advice-name" placeholder="Как вас зовут?">
                        </div>
                        <button class="advice-form__btn main-btn">Задать вопрос онлайн</button>
                    </form>

                    <div class="expert-login">
                        <h3 class="expert-login__title">Вы специалист в области права?</h3>
                        <div class="expert-login__desc">Вы можете отвечать на вопросы наших пользователей, пройдя нехитрую процедуру регистрации и подтверждение вашей квалификации.</div>
                        <a href="" class="expert-login__btn main-btn">Зарегистрироваться</a>
                    </div>

                    <div class="best-workers">
                        <h3 class="best-workers__title">Наши лучшие юристы</h3>
                        <div class="best-workers__item">
                            <div class="best-workers__avatar img">
                                <img src="../img/unregistered/best-workers-avatar-1.png" alt="">
                                <div class="best-workers__avatar-online"></div>
                            </div>
                            <div class="best-workers__data">
                                <a href="" class="best-workers__name">Александр Бударагин</a>
                                <div class="best-workers__data-wrapper">
                                    <div class="best-workers__specialty">Юрист</div>
                                    <div class="best-workers__location">
                                        <div class="best-workers__location-ico img">
                                            <img src="../img/unregistered/best-workers-location-ico.png" alt="">
                                        </div>
                                        <div class="best-workers__location-value">Нижний Новгород</div>
                                    </div>
                                </div>
                                <div class="best-workers__activity">
                                    <div class="best-workers__activity-value">86</div>
                                    <div class="best-workers__activity-title">консультаций</div>
                                </div>
                            </div>
                        </div>
                        <div class="best-workers__item">
                            <div class="best-workers__avatar img">
                                <img src="../img/unregistered/best-workers-avatar-2.png" alt="">
                            </div>
                            <div class="best-workers__data">
                                <a href="" class="best-workers__name">Марина Тарасова</a>
                                <div class="best-workers__data-wrapper">
                                    <div class="best-workers__specialty">Юрист</div>
                                    <div class="best-workers__location">
                                        <div class="best-workers__location-ico img">
                                            <img src="../img/unregistered/best-workers-location-ico.png" alt="">
                                        </div>
                                        <div class="best-workers__location-value">Москва</div>
                                    </div>
                                </div>
                                <div class="best-workers__activity">
                                    <div class="best-workers__activity-value">9</div>
                                    <div class="best-workers__activity-title">консультаций</div>
                                </div>
                            </div>
                        </div>
                        <div class="best-workers__item">
                            <div class="best-workers__avatar img">
                                <img src="../img/unregistered/best-workers-avatar-3.png" alt="">
                            </div>
                            <div class="best-workers__data">
                                <a href="" class="best-workers__name">Галина Гудкова</a>
                                <div class="best-workers__data-wrapper">
                                    <div class="best-workers__specialty">Юрист</div>
                                    <div class="best-workers__location">
                                        <div class="best-workers__location-ico img">
                                            <img src="../img/unregistered/best-workers-location-ico.png" alt="">
                                        </div>
                                        <div class="best-workers__location-value">Москва</div>
                                    </div>
                                </div>
                                <div class="best-workers__activity">
                                    <div class="best-workers__activity-value">9</div>
                                    <div class="best-workers__activity-title">консультаций</div>
                                </div>
                            </div>
                        </div>
                        <a href="" class="best-workers__btn main-btn">Все наши юристы</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>





