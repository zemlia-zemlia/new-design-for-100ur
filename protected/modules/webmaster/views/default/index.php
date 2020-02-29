<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */
/* @var $leadStatsByDates array */
/* @var $stat \webmaster\services\StatisticsService */

$this->pageTitle = "Личный кабинет вебмастера. " . Yii::app()->name;


?>


<h1>Кабинет вебмастера</h1>

<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= $stat->getAllLeadsCount((new DateTime())->modify('-29 days')->modify('midnight')) ?></h3>
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

<div class="box">
    <div class="box-header">
        <div class="box-title">Cтатистика по лидам за последние 15 дней</div>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th>Дата</th>
                <th>Получено заявок</th>
                <th>Выкуплено заявок</th>
                <th>Не выкуплено</th>
                <th>% Выкупа</th>
                <th>Брак (%)</th>
                <th>Дубли</th>
                <th>Средняя стоимость заявки</th>
                <th>Всего заработок</th>
            </tr>
            <?php foreach ($leadStatsByDates['dates'] as $date => $leadsByDatesRow):?>
                <tr>
                    <td><?php echo $date;?></td>
                    <td><?php echo $leadsByDatesRow['totalLeads'];?></td>
                    <td><?php echo $leadsByDatesRow['soldLeads'];?></td>
                    <td><?php echo $leadsByDatesRow['notSoldLeads'];?></td>
                    <td><?php echo $leadsByDatesRow['soldLeadsPercent'];?>%</td>
                    <td><?php echo $leadsByDatesRow['brakLeads'];?> (<?php echo $leadsByDatesRow['brakPercents'];?>%)</td>
                    <td><?php echo $leadsByDatesRow['duplicateLeads'];?></td>
                    <td><?php echo MoneyFormat::rubles($leadsByDatesRow['averageLeadPrice']);?></td>
                    <td><?php echo MoneyFormat::rubles($leadsByDatesRow['totalRevenue']);?></td>
                </tr>
            <?php endforeach;?>
            <tr>
                <td>Всего</td>
                <td><?php echo $leadStatsByDates['totalLeads'];?></td>
                <td><?php echo $leadStatsByDates['soldLeads'];?></td>
                <td><?php echo $leadStatsByDates['notSoldLeads'];?></td>
                <td><?php echo $leadStatsByDates['soldLeadsPercent'];?>%</td>
                <td><?php echo $leadStatsByDates['brakLeads'];?> (<?php echo $leadStatsByDates['brakPercents'];?>%)</td>
                <td><?php echo $leadStatsByDates['duplicateLeads'];?></td>
                <td><?php echo MoneyFormat::rubles($leadStatsByDates['averageLeadPrice']);?></td>
                <td><?php echo MoneyFormat::rubles($leadStatsByDates['totalRevenue']);?></td>
            </tr>
        </table>
    </div>
</div>

<div class="box">
    <div class="box-header">
        <div class="box-title">Cтатистика по регионам за последние 15 дней</div>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th>Название региона</th>
                <th>Получено заявок</th>
                <th>Выкуплено заявок</th>
                <th>% Выкупа</th>
                <th>Не выкуплено</th>
                <th>Брак (%)</th>
                <th>Дубли</th>
                <th>Средняя стоимость заявки</th>
                <th>Всего заработок</th>
            </tr>
        </table>
    </div>
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
