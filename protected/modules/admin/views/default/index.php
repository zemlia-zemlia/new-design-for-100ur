<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	$this->module->id,
);

Yii::app()->clientScript->registerScriptFile('/js/highcharts/js/highcharts.js');

?>
<h1>Добро пожаловать в админку!</h1>


<?php if(Yii::app()->user->checkAccess(User::ROLE_ROOT)):?>

<div id="chart_summa" style="width:100%; height:300px;"></div>

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
                <?php    foreach ($sumArray as $date=>$summa):?>
                    <?php echo '"'.$date.'"'.','; ?>                
                <?php  endforeach;?>                    
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
                <?php    foreach ($sumArray as $date=>$summa):?>
                    <?php echo $summa.','; ?>                
                <?php  endforeach;?>      
            ]
        },{
            name: 'Расходы',
            data: [
                <?php    foreach ($buySumArray as $date=>$summa):?>
                    <?php echo $summa.','; ?>                
                <?php  endforeach;?>      
            ]
        }]
    });
    
    
});
</script>

<h3>Процент вопросов, на которые ответ был дан в течение 4 часов (за последние 30 дней): <span class="label label-info"><?php echo $fastQuestionsRatio;?>%</span></h3>
<?php endif;?>