<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);

Yii::app()->clientScript->registerScriptFile('/js/highcharts/js/highcharts.js');

// массив направлений доходов и расходов
$moneyDirections = Money::getDirectionsArray();
$startYear = 2016;
$endYear = 2017;
?>
<h1>Добро пожаловать в админку!</h1>


<?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>

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
            name: 'Выручка',
            data: [
                <?php foreach ($sumArray as $year=>$summByMonth):?>
                    <?php foreach ($summByMonth as $month=>$summa):?>    
                        <?php echo '["' . $month . '.' . $year . '",' . $summa.'],'; ?>                
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

<h3>Процент вопросов, на которые ответ был дан в течение 4 часов (за последние 30 дней): <span class="label label-info"><?php echo $fastQuestionsRatio;?>%</span></h3>
<?php endif;?>