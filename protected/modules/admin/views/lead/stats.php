<?php

use App\helpers\DateHelper;
use App\models\Campaign;

$this->setPageTitle('Статистика продаж. ' . Yii::app()->name);

$monthsArray = [
    '1' => 'Январь',
    '2' => 'Февраль',
    '3' => 'Март',
    '4' => 'Апрель',
    '5' => 'Май',
    '6' => 'Июнь',
    '7' => 'Июль',
    '8' => 'Август',
    '9' => 'Сентябрь',
    '10' => 'Октябрь',
    '11' => 'Ноябрь',
    '12' => 'Декабрь',
];
Yii::app()->clientScript->registerScriptFile('/js/highcharts/js/highcharts.js');

?>


<div class="row">
    <div class="col-md-3">
        <h3>Cтатистика продаж</h3>
    </div>
    <div class="col-md-5">
        <div class="row">
            <form class="form-inline" role="form" action="">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <?php echo CHtml::dropDownList('month', $month, $monthsArray, [
                            'class' => 'form-control',
                            'style' => 'width:100%;'
                        ]); ?>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <?php echo CHtml::dropDownList('year', $year, $yearsArray, [
                            'class' => 'form-control',
                            'style' => 'width:100%;'
                        ]); ?>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <?php echo CHtml::submitButton('Показать', ['class' => 'btn btn-primary']); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4 center-align">
        <div class="vert-margin20">
            <?php echo CHtml::link('По датам', Yii::app()->createUrl('admin/lead/stats', ['type' => 'dates'])); ?>
            &nbsp;&nbsp;
            <?php echo CHtml::link('По кампаниям', Yii::app()->createUrl('admin/lead/stats', ['type' => 'campaigns'])); ?>
            &nbsp;&nbsp;
            <?php echo CHtml::link('Расходы', Yii::app()->createUrl('admin/expence')); ?>
        </div>
    </div>
</div>


<?php
$sumTotal = 0;
$kolichTotal = 0;
$buySumTotal = 0;
$profitTotal = 0;
$vipTotal = 0;
$expencesTotal = 0;
?>

<?php if (sizeof($sumArray)): ?>
    <!-- <div id="chart_kolich" style="width:100%; height:300px;"></div> -->
    <div id="chart_summa" style="width:100%; height:400px;"></div>
<?php endif; ?>

<div class="box">
    <table class="table table-bordered">
        <tr>
            <th>
                <?php
                switch ($type) {
                    case 'dates':
                        echo 'Дата';
                        break;
                    case 'campaigns':
                        echo 'Кампания';
                        break;
                }
                ?>
            </th>
            <th>Лиды</th>
            <th>Выручка</th>
            <th>VIP</th>
            <th>Покупка лидов</th>
            <th>Расход на контекст</th>
            <th>Прочие расходы</th>
            <th>Прибыль</th>
            <th>Марж</th>
        </tr>
        <?php foreach ($sumArray as $date => $summa): ?>
            <?php
            $sumTotal += $summa;
            $buySumTotal += $buySumArray[$date];
            $kolichTotal += $kolichArray[$date];
            $vipTotal += $vipStats[$date];
            $expencesDirectTotal += $expencesDirectArray[$date]['expence'];
            $expencesCallsTotal += $expencesCallsArray[$date]['expence'];
            $profit = $summa + $vipStats[$date] - $buySumArray[$date] - $expencesDirectArray[$date]['expence'] - $expencesCallsArray[$date]['expence'];
            $profitTotal += $profit;
            $marginPercent = round(($profit / ($summa + $vipStats[$date])) * 100); // маржинальность за день
            $marginPercentSum += $marginPercent;
            ?>
            <tr>
                <td>
                    <?php
                    switch ($type) {
                        case 'dates':
                            echo DateHelper::invertDate($date);
                            break;
                        case 'campaigns':
                            echo Campaign::getCampaignNameById($date);
                            break;
                    }
                    ?>
                </td>
                <td class="text-right"><?php echo $kolichArray[$date]; ?></td>
                <td class="text-right"><?php echo MoneyFormat::rubles($summa, true); ?></td>
                <td class="text-right"><?php echo MoneyFormat::rubles($vipStats[$date], true); ?></td>
                <td class="text-right"><?php echo MoneyFormat::rubles($buySumArray[$date], true); ?></td>
                <td class="text-right"><?php echo MoneyFormat::rubles($expencesDirectArray[$date]['expence'], true); ?></td>
                <td class="text-right"><?php echo MoneyFormat::rubles($expencesCallsArray[$date]['expence'], true); ?></td>
                <td class="text-right">
                    <?php
                    echo MoneyFormat::rubles($profit, true);
                    ?>
                </td>
                <td>
            <span class="<?php echo ($marginPercent >= 50) ? 'text-success' : 'text-warning'; ?>">
                <strong><?php echo $marginPercent; ?>%</strong>
            </span>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if ($kolichTotal): ?>
            <tr>
                <th>Всего</th>
                <th class="text-right"><?php echo $kolichTotal; ?></th>
                <th class="text-right"><?php echo MoneyFormat::rubles($sumTotal, true); ?> руб.</th>
                <th class="text-right"><?php echo MoneyFormat::rubles($vipTotal, true); ?> руб.</th>
                <th class="text-right"><?php echo MoneyFormat::rubles($buySumTotal, true); ?> руб.</th>
                <th class="text-right"><?php echo MoneyFormat::rubles($expencesDirectTotal, true); ?> руб.</th>
                <th class="text-right"><?php echo MoneyFormat::rubles($expencesCallsTotal, true); ?> руб.</th>
                <th class="text-right"><?php echo MoneyFormat::rubles($profitTotal, true); ?></th>
                <td>
            <span class="<?php echo (round($marginPercentSum / sizeof($sumArray)) >= 50) ? 'text-success' : 'text-warning'; ?>">
                <strong>
                    <?php echo round($marginPercentSum / sizeof($sumArray)); ?>%
                </strong>
            </span>
                </td>
            </tr>
        <?php endif; ?>

    </table>
</div>

<?php if (sizeof($sumArray)): ?>

    <?php
    ksort($sumArray);
    ?>

    <?php if ('dates' == $type): ?>
        <script type="text/javascript">
            $(function () {
                $('#chart_summa').highcharts({
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'Статистика продаж'
                    },
                    xAxis: {
                        categories: [
                            <?php    foreach ($sumArray as $date => $summa):?>
                            <?php echo '"' . $date . '"' . ','; ?>
                            <?php  endforeach; ?>
                        ]
                    },
                    yAxis: {
                        title: {
                            text: 'Продажи'
                        }
                    },
                    series: [{
                        name: 'Выручка',
                        data: [
                            <?php    foreach ($sumArray as $date => $summa):?>
                            <?php echo MoneyFormat::rubles($summa) . ','; ?>
                            <?php  endforeach; ?>
                        ]
                    },
                        {
                            name: 'Прибыль',
                            data: [
                                <?php    foreach ($sumArray as $date => $summa):?>
                                <?php echo MoneyFormat::rubles($summa + $vipStats[$date] - $buySumArray[$date] - $expencesDirectArray[$date]['expence'] - $expencesCallsArray[$date]['expence']) . ','; ?>
                                <?php  endforeach; ?>
                            ]
                        }
                    ]
                });

                $('#chart_kolich').highcharts({
                    chart: {
                        type: 'line'
                    },
                    title: {
                        text: 'Статистика продаж'
                    },
                    xAxis: {
                        categories: [
                            <?php    foreach ($sumArray as $date => $summa):?>
                            <?php echo '"' . $date . '"' . ','; ?>
                            <?php  endforeach; ?>
                        ]
                    },
                    yAxis: {
                        title: {
                            text: 'Лиды'
                        }
                    },
                    series: [{
                        name: 'Количество',
                        data: [
                            <?php    foreach ($sumArray as $date => $summa):?>
                            <?php echo $kolichArray[$date] . ','; ?>
                            <?php  endforeach; ?>
                        ]
                    }]
                });
            });
        </script>
    <?php endif; ?>

    <?php if ('campaigns' == $type): ?>
        <script type="text/javascript">
            $(function () {
                $('#chart_summa').highcharts({
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: 'Выручка'
                    },
                    series: [{
                        name: 'Выручка',
                        data: [
                            <?php foreach ($sumArray as $date => $summa):?>
                            {
                                name: '<?php echo Campaign::getCampaignNameById($date); ?>',
                                y: <?php echo MoneyFormat::rubles($summa); ?>
                            },
                            <?php  endforeach; ?>
                        ]
                    }]
                });

                $('#chart_kolich').highcharts({
                    chart: {
                        type: 'pie'
                    },
                    title: {
                        text: 'Количество'
                    },

                    series: [{
                        name: 'Количество',
                        data: [
                            <?php    foreach ($sumArray as $date => $summa):?>
                            {
                                name: '<?php echo Campaign::getCampaignNameById($date); ?>',
                                y: <?php echo $kolichArray[$date]; ?>
                            },
                            <?php  endforeach; ?>
                        ]
                    }]
                });
            });
        </script>
    <?php endif; ?>


<?php endif; ?>
