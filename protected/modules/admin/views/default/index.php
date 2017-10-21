<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);

Yii::app()->clientScript->registerScriptFile('/js/highcharts/js/highcharts.js');
Yii::app()->clientScript->registerScriptFile('/js/highcharts/js/modules/funnel.js');

// массив направлений доходов и расходов
$moneyDirections = Money::getDirectionsArray();
$startYear = 2016;
$endYear = 2017;
?>
<h1>Добро пожаловать в админку!</h1>


<?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>

<div class="panel panel-default">
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
                <?php for($y=$startYear; $y<=$endYear; $y++):?>
                    <?php for($m=1; $m<=12; $m++):?>
                        <?php echo '"'.$m.'.'. $y .'"'.','; ?>  
                    <?php  endfor;?> 
                <?php  endfor;?>                    
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
                <?php foreach ($sumArray as $year=>$summByMonth):?>
                    <?php foreach ($summByMonth as $month=>$summa):?>    
                        <?php echo '["' . $month . '.' . $year . '",' . floor($summa + $vipArray[$year][$month]) . '],'; ?>                
                    <?php  endforeach;?>
                <?php  endforeach;?>
            ]
        },{
            name: 'Покупка лидов',
            data: [
                <?php foreach ($buySumArray as $year=>$summByMonth):?>
                    <?php foreach ($summByMonth as $month=>$summa):?>    
                        <?php echo '["' . $month . '.' . $year . '",' . $summa.'],'; ?>                
                    <?php  endforeach;?>
                <?php  endforeach;?>
            ]
        },
        <?php foreach($moneyFlow as $directionId=>$flow):?>
        {
            name: '<?php echo $moneyDirections[$directionId];?>',
            data: [
                <?php foreach ($flow as $year=>$summByMonth):?>
                    <?php foreach ($summByMonth as $month=>$summa):?>    
                        <?php echo '["' . $month . '.' . $year . '",' . abs($summa).'],'; ?>                
                    <?php  endforeach;?>
                <?php  endforeach;?>
            ]
        },
        <?php endforeach;?>
        {
            name: 'Сумма расходов',
            data: [
                <?php foreach ($totalExpences as $year=>$summByMonth):?>
                    <?php foreach ($summByMonth as $month=>$summa):?>    
                        <?php echo '["' . $month . '.' . $year . '",' . $summa.'],'; ?>                
                    <?php  endforeach;?>
                <?php  endforeach;?>
            ]
        },
        ]
    });
    
    
});
</script>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <h3>Процент вопросов, на которые ответ был дан в течение 4 часов (за последние 30 дней): <span class="label label-info"><?php echo $fastQuestionsRatio;?>%</span></h3>
        </div>
    </div>
    <div class="col-md-6">
        <h3>Опубликованные вопросы</h3>
        <table class="table">
            <tr>
                <?php foreach($publishedQuestionsCount as $date=>$counter):?>
                    <td class="center-align"><small><?php echo date('d.m', strtotime($date));?></small></td>
                <?php endforeach;?>
            </tr>
            <tr>
                <?php foreach($publishedQuestionsCount as $date=>$counter):?>
                    <td class="center-align"><?php echo $counter;?></td>
                <?php endforeach;?>
            </tr>
        </table>
    </div>
</div>

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
                <?php foreach($uniqueLeadDates as $leadDate):?>
                        '<?php echo date('d.m', strtotime($leadDate)); ?>', 
                <?php  endforeach;?>                    
            ]
        },
        yAxis: {
            title: {
                text: 'Лиды'
            }
        },
        series: [
            <?php foreach($leadsByTypes as $type=>$leadsByDates):?>
            {
            name: '<?php echo Lead100::getLeadTypesArray()[$type];?>',
            data: [
                <?php foreach ($uniqueLeadDates as $date):?>
                        <?php echo '["' . date('d.m', strtotime($date)) . '",' . (int)$leadsByTypes[$type][$date] . '],'; ?>                
                <?php  endforeach;?>
                ]
            },
            <?php endforeach;?>
        ]
    });
    
    
});
</script>

<?php endif;?>