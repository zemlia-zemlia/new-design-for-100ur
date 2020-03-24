<?php
/* @var $this ContactController */
/* @var $dataProvider CActiveDataProvider */
/* @var $leadStatsByDates array */
/* @var $leadStatsByRegions array */
/* @var $statsFor30Days array */
/* @var $activeCampaignsCount int */
/* @var $stat \webmaster\services\StatisticsService */

$this->pageTitle = 'Личный кабинет вебмастера. ' . Yii::app()->name;

?>


<h1 class="text-center">Кабинет вебмастера</h1>

<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?php echo $statsFor30Days['totalLeads'] ? $statsFor30Days['totalLeads'] : 0; ?></h3>
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
                <h3><?php echo $statsFor30Days['soldLeadsPercent'] ? $statsFor30Days['soldLeadsPercent']  : 0; ?><sup style="font-size: 20px">%</sup></h3>

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
                <h3><?php echo MoneyFormat::rubles($statsFor30Days['totalRevenue']); ?> </h3>

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
                <h3><?php echo $activeCampaignsCount; ?></h3>

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
                <th>Брак (%)</th>
                <th>Дубли</th>
                <th>Ср цена лида</th>
                <th>Всего заработок</th>
            </tr>
            <?php if (isset($leadStatsByDates['data'])): ?>
                <?php foreach ($leadStatsByDates['data'] as $date => $leadsByDatesRow): ?>
                    <tr>
                        <td><?php echo DateHelper::niceDate($date, false, false, false); ?></td>
                        <td><?php echo $leadsByDatesRow['totalLeads']; ?></td>
                        <td><?php echo $leadsByDatesRow['soldLeads']; ?>
                            (<?php echo $leadsByDatesRow['soldLeadsPercent']; ?>%)
                        </td>
                        <td><?php echo $leadsByDatesRow['notSoldLeads']; ?></td>
                        <td><?php echo $leadsByDatesRow['brakLeads']; ?>
                            (<?php echo $leadsByDatesRow['brakPercents']; ?>
                            %)
                        </td>
                        <td><?php echo $leadsByDatesRow['duplicateLeads']; ?></td>
                        <td><?php echo MoneyFormat::rubles($leadsByDatesRow['averageLeadPrice']); ?></td>
                        <td><?php echo MoneyFormat::rubles($leadsByDatesRow['totalRevenue']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td>Всего</td>
                    <td><?php echo $leadStatsByDates['totalLeads']; ?></td>
                    <td><?php echo $leadStatsByDates['soldLeads']; ?>
                        (<?php echo $leadStatsByDates['soldLeadsPercent']; ?>%)
                    </td>
                    <td><?php echo $leadStatsByDates['notSoldLeads']; ?></td>
                    <td><?php echo $leadStatsByDates['brakLeads']; ?> (<?php echo $leadStatsByDates['brakPercents']; ?>
                        %)
                    </td>
                    <td><?php echo $leadStatsByDates['duplicateLeads']; ?></td>
                    <td><?php echo MoneyFormat::rubles($leadStatsByDates['averageLeadPrice']); ?></td>
                    <td><?php echo MoneyFormat::rubles($leadStatsByDates['totalRevenue']); ?></td>
                </tr>
            <?php endif; ?>
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
                <th>Не выкуплено</th>
                <th>Брак (%)</th>
                <th>Дубли</th>
                <th>Ср цена лида</th>
                <th>Всего заработок</th>
            </tr>
            <?php if (isset($leadStatsByRegions['data'])): ?>
                <?php foreach ($leadStatsByRegions['data'] as $regionName => $leadsByDatesRow): ?>
                    <tr>
                        <td><?php echo $regionName; ?></td>
                        <td><?php echo $leadsByDatesRow['totalLeads']; ?></td>
                        <td><?php echo $leadsByDatesRow['soldLeads']; ?>
                            (<?php echo $leadsByDatesRow['soldLeadsPercent']; ?>%)
                        </td>
                        <td><?php echo $leadsByDatesRow['notSoldLeads']; ?></td>
                        <td><?php echo $leadsByDatesRow['brakLeads']; ?>
                            (<?php echo $leadsByDatesRow['brakPercents']; ?>
                            %)
                        </td>
                        <td><?php echo $leadsByDatesRow['duplicateLeads']; ?></td>
                        <td><?php echo MoneyFormat::rubles($leadsByDatesRow['averageLeadPrice']); ?></td>
                        <td><?php echo MoneyFormat::rubles($leadsByDatesRow['totalRevenue']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td>Всего</td>
                    <td><?php echo $leadStatsByRegions['totalLeads']; ?></td>
                    <td><?php echo $leadStatsByRegions['soldLeads']; ?>
                        (<?php echo $leadStatsByRegions['soldLeadsPercent']; ?>%)
                    </td>
                    <td><?php echo $leadStatsByRegions['notSoldLeads']; ?></td>
                    <td><?php echo $leadStatsByRegions['brakLeads']; ?>
                        (<?php echo $leadStatsByRegions['brakPercents']; ?>
                        %)
                    </td>
                    <td><?php echo $leadStatsByRegions['duplicateLeads']; ?></td>
                    <td><?php echo MoneyFormat::rubles($leadStatsByRegions['averageLeadPrice']); ?></td>
                    <td><?php echo MoneyFormat::rubles($leadStatsByRegions['totalRevenue']); ?></td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<div class="box">
    <div class="box-header">
        <div class="box-title">Последние лиды</div>
    </div>

</div>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => 'application.modules.webmaster.views.lead._view',
    'emptyText' => 'Не найдено ни одного лида',
    'summaryText' => 'Показаны лиды с {start} до {end}, всего {count}',
    'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
]); ?>


<!-- 
<div class="vert-margin40">
    <h2>Мои вопросы</h2>
    <table class="table table-bordered table-hover table-striped">
    <?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $questionsDataProvider,
    'itemView' => 'application.modules.webmaster.views.question._view',
    'emptyText' => 'Не найдено ни одного вопроса',
    'summaryText' => 'Показаны лиды с {start} до {end}, всего {count}',
    'pager' => ['class' => 'GTLinkPager'], //we use own pager with russian words
]); ?>
    </table>
</div>

-->
