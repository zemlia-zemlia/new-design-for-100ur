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
          
    </div>
</div>



<div class="panel panel-default">
<div id="chart_questions" style="width:100%; height:500px;"></div>

<script type="text/javascript">
$(function () { 
    $('#chart_questions').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Вопросы по статусам'
        },
        xAxis: {
            categories: [
                
                    <?php echo implode(', ', array_keys($questionByWeekArray));?>
            ]
        },
        yAxis: {
            title: {
                text: 'Вопросы'
            }
        },
        series: [{
            name: 'Всего',
            data: [
                <?php foreach ($questionByWeekArray as $week=>$questionsByWeek):?>
                        <?php echo '[' .$week . ', '. $questionsByWeek['total'] .'],'; ?>                
                <?php  endforeach;?>
            ]
        },
        {
            name: 'Недозаполненные',
            data: [
                <?php foreach ($questionByWeekArray as $week=>$questionsByWeek):?>
                        
                        <?php echo '[' .$week . ', '. $questionsByWeek[Question::STATUS_PRESAVE]['total'] .'],'; ?>                
                <?php  endforeach;?>
            ]
        },
        {
            name: 'Email не указан',
            data: [
                <?php foreach ($questionByWeekArray as $week=>$questionsByWeek):?>
                        
                        <?php echo '[' .$week . ', '. $questionsByWeek[Question::STATUS_NEW]['no_email'] .'],'; ?>                
                <?php  endforeach;?>
            ]
        },
        {
            name: 'Email не подтвержден',
            data: [
                <?php foreach ($questionByWeekArray as $week=>$questionsByWeek):?>
                        
                        <?php echo '[' .$week . ', '. $questionsByWeek[Question::STATUS_NEW]['with_email'] .'],'; ?>                
                <?php  endforeach;?>
            ]
        },
        {
            name: 'Опубликован',
            data: [
                <?php foreach ($questionByWeekArray as $week=>$questionsByWeek):?>
                        <?php echo '[' .$week . ', '. ($questionsByWeek[Question::STATUS_CHECK]['total'] + $questionsByWeek[Question::STATUS_PUBLISHED]['total']) .'],'; ?>                
                <?php  endforeach;?>
            ]
        },
        ]
    });
    
    
});
</script>
</div>

<?php endif;?>