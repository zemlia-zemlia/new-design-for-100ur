<?php
/* @var $this DefaultController */

use App\models\Lead;
use App\models\Money;
use App\models\User;

$this->breadcrumbs = [
    $this->module->id,
];

Yii::app()->clientScript->registerScriptFile('/js/highcharts/js/highcharts.js');
Yii::app()->clientScript->registerScriptFile('/js/highcharts/js/modules/funnel.js');

// массив направлений доходов и расходов
$moneyDirections = Money::getDirectionsArray();
$startYear = 2016;
$endYear = 2019;
?>
    <h1>Добро пожаловать в админку!</h1>


<?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>

    <div class="box">
        <div class="box-body">
            <div id="chart_summa" style="width:100%; height:500px;"></div>

            <script type="text/javascript">
                $(function () {
                    $('#chart_summa').highcharts({
                        chart: {
                            type: 'line'
                        },
                        title: {
                            text: 'Доходы и расходы'
                        },
                        xAxis: {
                            categories: [
                                <?php for ($y = $startYear; $y <= $endYear; ++$y): ?>
                                <?php for ($m = 1; $m <= 12; ++$m): ?>
                                <?php echo '"' . $m . '.' . $y . '"' . ','; ?>
                                <?php endfor; ?>
                                <?php endfor; ?>
                            ]
                        },
                        yAxis: {
                            title: {
                                text: 'Доходы и расходы'
                            }
                        },
                        series: [{
                            name: 'Выручка (с VIP вопросами)',
                            data: [
                                <?php foreach ($sumArray as $year => $summByMonth): ?>
                                <?php for ($month = 1; $month <= 12; ++$month): ?>
                                <?php echo '["' . $month . '.' . $year . '",' . MoneyFormat::rubles($summByMonth[$month] + $vipArray[$year][$month]) . '],'; ?>
                                <?php endfor; ?>
                                <?php endforeach; ?>
                            ]
                        }, {
                            name: 'Покупка лидов',
                            data: [
                                <?php foreach ($buySumArray as $year => $summByMonth): ?>
                                <?php for ($month = 1; $month <= 12; ++$month): ?>
                                <?php echo '["' . $month . '.' . $year . '",' . MoneyFormat::rubles($summByMonth[$month]) . '],'; ?>
                                <?php endfor; ?>
                                <?php endforeach; ?>
                            ]
                        },
                            <?php foreach ($moneyFlow as $directionId => $flow): ?>
                            {
                                name: '<?php echo $moneyDirections[$directionId]; ?>',
                                data: [
                                    <?php foreach ($flow as $year => $summByMonth): ?>
                                    <?php for ($month = 1; $month <= 12; ++$month): ?>
                                    <?php echo '["' . $month . '.' . $year . '",' . MoneyFormat::rubles(abs($summByMonth[$month])) . '],'; ?>
                                    <?php endfor; ?>
                                    <?php endforeach; ?>
                                ]
                            },
                            <?php endforeach; ?>
                        ]
                    });
                });
            </script>
        </div>
    </div>

    <div class="row">

        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Опубликованные вопросы по дням</div>
                </div>
                <div class="box-body">
                    <table class="table">
                        <tr>
                            <?php
                            foreach ($statsService->getPublishedQuestionsCount() as $date => $counter): ?>
                                <td class="center-align">
                                    <small><?php echo date('d.m', strtotime($date)); ?></small>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <?php foreach ($statsService->getPublishedQuestionsCount() as $date => $counter): ?>
                                <td class="center-align"><?php echo $counter; ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <?php foreach ($statsService->getCountAnsversByDate() as $date => $counter): ?>
                                <td class="center-align"><?php echo $counter; ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <tr>
                            <?php foreach ($statsService->getPublishedCommentCount() as $date => $counter): ?>
                                <td class="center-align"><?php echo $counter; ?></td>
                            <?php endforeach; ?>
                        </tr>


                    </table>

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Вопросы и ответы на них (за последние 30 дней):</div>
                </div>
                <div class="box-body">
                    <span>Всего вопросов поступило: <?php echo $questionPublishedInRecentDays; ?></span><br/>
                    <span>Вопросов на которые дан ответ: <?php echo $answersMadeInRecentDays; ?>
                        <?php if ($questionPublishedInRecentDays > 0) {
    echo ' (' . (int) ($answersMadeInRecentDays / $questionPublishedInRecentDays * 100) . '%)';
}
                        ?>
                    </span><br/>
                    <span>Среднее время ответа на вопрос: <?php echo $averageIntervalUntillAnswer; ?> ч</span><br/>
                </div>
            </div>
        </div>
    </div>

    <div class="box">
        <div class="box-body">
            <div id="chart_leads_100" style="width:100%; height:500px;"></div>

            <script type="text/javascript">
                $(function () {
                    $('#chart_leads_100').highcharts({
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Лиды с источником 100 Юристов'
                        },
                        xAxis: {
                            categories: [
                                <?php foreach ($stat100yuristov as $date => $counter): ?>
                                '<?php echo $date; ?>',
                                <?php endforeach; ?>
                            ],
                            crosshair: true
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: ''
                            }
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        series: [{
                            name: 'Лиды с источником 100 Юристов',
                            data: [
                                <?php foreach ($stat100yuristov as $date => $counter): ?>
                                <?php echo $counter; ?>,
                                <?php endforeach; ?>
                            ]

                        }]
                    });
                });
            </script>
        </div>
    </div>

    <div class="box">
        <div class="box-body">
            <div id="chart_leads" style="width:100%; height:500px;"></div>

            <script type="text/javascript">
                $(function () {
                    $('#chart_leads').highcharts({
                        chart: {
                            type: 'line'
                        },
                        title: {
                            text: 'Лиды по типам'
                        },
                        xAxis: {
                            categories: [
                                <?php foreach ($uniqueLeadDates as $leadDate): ?>
                                '<?php echo date('d.m', strtotime($leadDate)); ?>',
                                <?php endforeach; ?>
                            ]
                        },
                        yAxis: {
                            title: {
                                text: 'Лиды'
                            }
                        },
                        series: [
                            <?php foreach ($leadsByTypes as $type => $leadsByDates): ?>
                            {
                                name: '<?php echo Lead::getLeadTypesArray()[$type]; ?>',
                                data: [
                                    <?php foreach ($uniqueLeadDates as $date): ?>
                                    <?php echo '["' . date('d.m', strtotime($date)) . '",' . (int) $leadsByTypes[$type][$date] . '],'; ?>
                                    <?php endforeach; ?>
                                ]
                            },
                            <?php endforeach; ?>
                        ]
                    });
                });</script>
        </div>
    </div>

    <div class="box">
        <div class="box-header">
            <div class="box-title">Активность юристов по дням</div>
        </div>
        <div class="box-body">
            <?php $this->widget('application.widgets.UserActivity.UserActivityWidget', [
                'userId' => null,
                'role' => User::ROLE_JURIST,
            ]); ?>
        </div>
    </div>

    <div class="box small">
        <div class="box-header">
            <div class="box-title">Последние записи лога</div>
        </div>
        <div class="box-body">
            <?php
            // выводим виджет с последними записями лога
            $this->widget('application.widgets.LogReader.LogReaderWidget');
            ?>
        </div>
    </div>
<?php endif; ?>