<?php
    $this->setPageTitle("Статистика продаж. ". Yii::app()->name);
    
    $monthsArray = array(
        '1'     =>  'Январь',
        '2'     =>  'Февраль',
        '3'     =>  'Март',
        '4'     =>  'Апрель',
        '5'     =>  'Май',
        '6'     =>  'Июнь',
        '7'     =>  'Июль',
        '8'     =>  'Август',
        '9'     =>  'Сентябрь',
        '10'     => 'Октябрь',
        '11'    =>  'Ноябрь',
        '12'    =>  'Декабрь',
    );
    Yii::app()->clientScript->registerScriptFile('/js/highcharts/js/highcharts.js');
    
?>

<h1 class="vert-margin30">Статистика продаж</h1>

<form class="form-inline vert-margin30" role="form" action="">
    Статистика за месяц: 
    <div class="form-group">
        <?php echo CHtml::dropDownList("month", $month, $monthsArray, array('class'=>'form-control'));?>
    </div>
    <div class="form-group">
        <?php echo CHtml::dropDownList("year", $year, $yearsArray, array('class'=>'form-control'));?>
    </div>
    <div class="form-group">
        <?php echo CHtml::submitButton("Показать", array('class'=>'btn btn-primary'));?>
    </div>
</form> 

<div class="vert-margin30">
    <?php echo CHtml::link('По датам', Yii::app()->createUrl('admin/lead/stats',array('type'=>'dates')));?> &nbsp;&nbsp;
    <?php echo CHtml::link('По кампаниям', Yii::app()->createUrl('admin/lead/stats',array('type'=>'campaigns')));?> &nbsp;&nbsp;
</div>


<?php
    $sumTotal = 0;
    $kolichTotal = 0;
    $buySumTotal = 0;
    $profitTotal = 0;
?>

<?php if(sizeof($sumArray)):?>
<!-- <div id="chart_kolich" style="width:100%; height:300px;"></div> -->
<div id="chart_summa" style="width:100%; height:300px;"></div>
<?php endif;?>

<table class="table table-bordered">
    <tr>
        <th>
            <?php
                switch($type) {
                    case "dates":
                        echo "Дата";
                        break;
                    case "campaigns":
                        echo "Кампания";
                        break;
                }
            ?>
        </th>
        <th>Количество</th>
        <th>Выручка</th>
		<th>Расход на лиды</th>
		<th>Расход на контекст</th>
		<th>Прибыль</th>
    </tr>
    <?php    foreach ($sumArray as $date=>$summa):?>
    <?php
        $sumTotal += $summa;
        $buySumTotal += $buySumArray[$date];
        $kolichTotal += $kolichArray[$date];
        $profit = $summa - $buySumArray[$date] - $expencesArray[$date];
        $profitTotal += $profit;
    ?>
    <tr>
        <td>
            <?php
                switch($type) {
                    case "dates":
                        echo CustomFuncs::invertDate($date);
                        break;
                    case "campaigns":
                        echo Campaign::getCampaignNameById($date);
                        break;
                }
            ?>
        </td>
        <td><?php echo $kolichArray[$date];?></td>
        <td><?php echo $summa;?></td>
        <td><?php echo $buySumArray[$date];?></td>
        <td><?php echo (int)$expencesArray[$date];?></td>
        <td>
            <?php
                echo $profit;
            ?>
        </td>
    </tr>
    <?php  endforeach;?>
    
    <?php if($kolichTotal):?>
    <tr>
        <th>Всего</th>
        <th><?php echo $kolichTotal;?></th>
        <th><?php echo $sumTotal;?> руб.</th>
        <th><?php echo $buySumTotal;?> руб.</th>
        <th></th>
        <th><?php echo $profitTotal;?></th>
    </tr>
    <?php endif;?>
	
</table>

<?php if(sizeof($sumArray)):?>

<?php 
    ksort($sumArray);
?>

<?php if($type == 'dates'):?>
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
        }]
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
                <?php    foreach ($sumArray as $date=>$summa):?>
                    <?php echo '"'.$date.'"'.','; ?>                
                <?php  endforeach;?>                    
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
                <?php    foreach ($sumArray as $date=>$summa):?>
                    <?php echo $kolichArray[$date].','; ?>                
                <?php  endforeach;?> 
            ]
        }]
    });
});
</script>
<?php endif;?>

<?php if($type == 'campaigns'):?>
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
            <?php foreach ($sumArray as $date=>$summa):?>
                {
            name: '<?php echo Campaign::getCampaignNameById($date);?>',
            y: <?php echo $summa; ?>                
            },
            <?php  endforeach;?>
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
                <?php    foreach ($sumArray as $date=>$summa):?>
                  {
                    name: '<?php echo Campaign::getCampaignNameById($date);?>',
                    y: <?php echo $kolichArray[$date]; ?>                
                    },
                <?php  endforeach;?> 
            ]
        }]
    });
});
</script>
<?php endif;?>


<?php endif;?>
