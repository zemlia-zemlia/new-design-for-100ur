<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Личный кабинет вебмастера. " . Yii::app()->name;


?>


<h1>Кабинет вебмастера</h1>

<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>150</h3>
                <p>Лидов за 30 дней</p>
            </div>
            <div class="icon">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>

                <p>Лидов выкуплено</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>44.553 </h3>

                <p>Заработок за 30 дней</p>
            </div>
            <div class="icon">
                <i class="fa fa-rub"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>65</h3>

                <p>Выкупаемых регионов</p>
            </div>
            <div class="icon">
                <i class="fa fa-map-marker"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
</div>

<h3>Последние лиды</h3>
<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => 'application.modules.webmaster.views.lead._view',
    'emptyText' => 'Не найдено ни одного лида',
    'summaryText' => 'Показаны лиды с {start} до {end}, всего {count}',
    'pager' => array('class' => 'GTLinkPager') //we use own pager with russian words
)); ?>


<!-- 
<div class="vert-margin40">
    <h2>Мои вопросы</h2>
    <table class="table table-bordered table-hover table-striped">
    <?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $questionsDataProvider,
    'itemView' => 'application.modules.webmaster.views.question._view',
    'emptyText' => 'Не найдено ни одного вопроса',
    'summaryText' => 'Показаны лиды с {start} до {end}, всего {count}',
    'pager' => array('class' => 'GTLinkPager') //we use own pager with russian words
)); ?>
    </table>
</div>

-->
