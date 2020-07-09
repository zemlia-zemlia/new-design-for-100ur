<?php

use App\helpers\DateHelper;

$monthsNames = DateHelper::getMonthsNames();

$pageTitle = 'Архив вопросов за ' . $monthsNames[$month] . ' ' . $year . ' года. ';
if (isset($_GET) && (int) $_GET['Question_page']) {
    $pageNumber = (int) $_GET['Question_page'];
    $pagesTotal = ceil($dataProvider->totalItemCount / $dataProvider->pagination->getPageSize());
    $pageTitle .= 'Страница ' . $pageNumber . ' из ' . $pagesTotal . '. ';
}
$this->setPageTitle($pageTitle);
Yii::app()->clientScript->registerMetaTag('Ответы юристов и адвокатов. ' . $pageTitle, 'Description');
Yii::app()->clientScript->registerLinkTag('canonical', null, Yii::app()->createUrl('/question/archive', ['date' => $year . '-' . $month]));

?>
<main class="main archive">
    <div class="container">
        <h2 class="archive__title main__title">Архив вопросов за <?php echo $monthsNames[$month] . ' ' . $year; ?> года</h2>
        <div class="row justify-content-between">
            <div class="col-md-7 col-lg-8">
                <div class="archive-questions">
                    <ul class="archive__list">



                            <?php foreach ($datesArray as  $index => $monthArchive): ?>
                                <?php if (0 == $index % 6) : ?>
                                    <div class="archive__list-wrap">
                                        <?php endif; ?>
                                            <li class="archive__list-item">
                                            <?php if ($monthArchive != $month):?>
                                                <?= CHtml::link($monthsNames[$monthArchive], Yii::app()->createUrl('question/archive', ['date' => $year . '-' . $monthArchive]),
                                                    ['class' => (Yii::app()->request->requestUri ==  Yii::app()->createUrl('question/archive', ['date' => $year . '-' . $monthArchive])) ?
                                                        'archive__list-link archive__list-link--active' : 'archive__list-link archive__list-link']
                                                ); ?>
                                            <?php else:?>
                                                <span class="text-muted"><?= $monthsNames[$monthArchive]; ?></span>
                                             <?php endif; ?>

                                             </li>
                                <?php if (5 == $index % 6) : ?>
                                    </div>
                            <?php endif; ?>


                            <?php endforeach; ?>


                    </ul>



                    <div class="archive-questions__body">

                        <?php $this->widget('zii.widgets.CListView', [
                            'dataProvider' => $dataProvider,
                            'itemView' => '_viewArchive',
                            'summaryText' => '',
                            'ajaxUpdate' => false,
                            'pager' => ['class' => 'GTLinkPager'],
                        ]); ?>

                        <a href="" class="archive-questions__more-btn">Показать еще 25 вопросов</a>
                        <div class="pagination">
                            <ul class="pagination__list">
                                <li class="pagination__list-item">
                                    <a href="" class="pagination__list-link pagination__list-link--active">1</a>
                                </li>
                                <li class="pagination__list-item">
                                    <a href="" class="pagination__list-link">2</a>
                                </li>
                                <li class="pagination__list-item">
                                    <a href="" class="pagination__list-link">3</a>
                                </li>
                                <li class="pagination__list-item">
                                    <div class="pagination__list-link">...</div>
                                </li>
                                <li class="pagination__list-item">
                                    <a href="" class="pagination__list-link">11</a>
                                </li>
                            </ul>
                            <a href="" class="pagination__btn">следующая</a>
                        </div>
                    </div>
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





