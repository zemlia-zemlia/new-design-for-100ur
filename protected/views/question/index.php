<?php
/* @var $this QuestionController */

use App\helpers\DateHelper;
use App\helpers\NumbersHelper;
use App\helpers\StringHelper;
use App\models\User;

/* @var $dataProvider CActiveDataProvider */

Yii::app()->clientScript->registerLinkTag('canonical', null, Yii::app()->createUrl('q'));

$pageTitle = 'Последние советы юристов бесплатно онлайн ';

$this->setPageTitle($pageTitle);

Yii::app()->clientScript->registerMetaTag('Советы юристов по всем отраслям права задайте свой вопрос и получите ответ в течении 15 минут', 'description');
$monthsNames = DateHelper::getMonthsNames();
?>


<main class="main">
    <div class="container">
        <h2 class="main__title">Вопросы юристам</h2>
        <div class="row justify-content-between">
            <div class="col-md-7 col-lg-8">
                <div class="questions-lawyers">
                    <?php foreach ($questions as $question): ?>
                    <div class="questions-lawyers__item">
                        <div class="questions-lawyers__item-price img">
                            <img src="/img/questions-lawyers-item-price.png" alt="">
                        </div>
                        <div class="questions-lawyers__item-title"><?= StringHelper::mb_ucfirst($question->title, 'utf-8') ?></div>
                        <div class="questions-lawyers__item-answer"><?= StringHelper::cutString($question->questionText, 70) ?></div>
                        <div class="questions-lawyers__item-wrapper">
                            <div class="questions-lawyers__item-date"><?= DateHelper::niceDate($question->createDate, false) ?></div>
                            <div class="questions-lawyers__item-location"><?= StringHelper::mb_ucfirst($question->authorName, 'utf-8') ?><?= $question->town? ', г. ' . $question->town->name : '' ?></div>
                            <a href="" class="questions-lawyers__item-category"><?= $question->categories ?  $question->categories[0]->name : '' ?></a>
                        </div>



                            <?php if ($question->answersCount > 0) : ?>
                                <?= CHtml::link(NumbersHelper::numForms($question->answersCount, 'ответ', 'ответа', 'ответов', true) ,
                                    Yii::app()->createUrl('question/view', ['id' => $question->id]), ['class' => 'archive-questions__btn questions-lawyers__item-btn']); ?>

                            <?php else: ?>
                                <div class="archive-questions__no-answer">Нет ответа</div>
                            <?php endif; ?>


                    </div>
                    <?php endforeach; ?>



                    <div class="archive-table">
                        <div class="archive-table__title">Архив вопросов</div>
                        <?php foreach ($datesArray as $year => $months): ?>

                        <div class="archive-table__item">
                            <div class="archive-table__item-year"><?= $year; ?></div>
                            <ul class="archive__list">
                                <?php foreach ($months as $index => $month): ?>
                                <?php if (0 == $index % 6) : ?>
                                <div class="archive__list-wrap">
                                   <?php endif; ?>
                                    <li class="archive__list-item">
                                        <?= CHtml::link($monthsNames[$month], Yii::app()->createUrl('question/archive', ['date' => $year . '-' . $month]),
                                            ['class' => (Yii::app()->request->requestUri ==  Yii::app()->createUrl('question/archive', ['date' => $year . '-' . $month])) ?
                                                'archive__list-link archive__list-link--active' : 'archive__list-link archive__list-link']) ?>
                                    </li>


                                    <?php if (5 == $index % 6) : ?>
                                </div>
                            <?php endif; ?>

                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <?php endforeach; ?>
                    </div>





                </div>
            </div>
            <div class=" col-md-5 col-lg-4">
                <div class="archive__aside">
                    <?php
                    // выводим виджет с формой
                    $this->widget('application.widgets.SimpleForm.SimpleForm', array(
                        'template' => 'sidebar',
                    ));
                    ?>

                    <?php if (Yii::app()->user->isGuest): ?>
                        <div class="expert-login">
                            <h3 class="expert-login__title">Вы специалист в области права?</h3>
                            <div class="expert-login__desc">Вы можете отвечать на вопросы наших пользователей, пройдя нехитрую процедуру регистрации и подтверждение вашей квалификации.</div>
                            <?php echo CHtml::link('Зарегистрироваться', Yii::app()->createUrl('/user/create', array('role' => User::ROLE_JURIST)),
                                ['class' => 'expert-login__btn main-btn']); ?>

                        </div>
                    <?php endif; ?>


                    <?php
                    // выводим виджет с топовыми юристами
                    $this->widget('application.widgets.TopYurists.TopYurists', array(
                        'cacheTime' => 30,
                        'limit' => 3,
                        'fetchType' => \TopYurists::FETCH_RANKED,
                        'template' => 'shortList',
                    ));
                    ?>



                </div>
            </div>
        </div>
    </div>
</main>





