<?php

use App\helpers\DateHelper;
use App\models\User;

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

$js = 'var arhiveUrl = "' . $url . '";';

$js .= <<< JS

$('#archive-questions-list').on('click', '.archive-questions__more-btn', function(e) {
    console.log(arhiveUrl);
    var perpage =  Number($(this).attr('data-per_page'));
   e.preventDefault();
        $.ajax({
  type: "POST",
  url: arhiveUrl,
  dataType: "html",
  data: { per_page: (perpage + 25) },
  success: function(msg){

    $('#archive-questions-list').html(msg);


  }});
        
})

JS;

Yii::app()->clientScript->registerScript('ajaxQuestionView', $js, CClientScript::POS_END);


?>
<main class="main">
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

                </div>

                    <div id="archive-questions-list" class="archive-questions">

                        <?php $this->widget('zii.widgets.CListView', [
                            'dataProvider' => $dataProvider,
                            'itemView' => '_viewArchive',
                            'htmlOptions' => ['class' => 'archive-questions__body'],
                            'ajaxUpdate' => true,
                            'pager' => ['class' => 'GTLinkPager'],
                            'pagerCssClass' => 'pagination',
                            'template' => '{items} <a href="#" data-per_page="25" class="archive-questions__more-btn">Показать еще 25 вопросов</a> {pager}'
                        ]); ?>



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





