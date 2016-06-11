<?php
/* @var $this StatController */

$this->pageTitle = "Мои показатели. " . Yii::app()->name;

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

?>

<div class="vert-margin30">
<h1>Мои показатели.
<?php echo $monthsArray[$month] . ' ' . $year; ?>
</h1>
</div>

<form class="form-inline vert-margin30" role="form" action="<?php echo Yii::app()->createUrl('user/myStats',array('officeId'=>$office->id)); ?>">
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


<table class='table table-bordered'>
    <tr>
        <th>Контакты</th>
        <th>Договоры</th>
    </tr>
    <tr>
        <td>
            Всего: <?php echo $leadsArray['total'];?><br />
            В работе: <?php echo $leadsArray['active'];?><br />
            Брак: <?php echo $leadsArray['brak'];?>
            <?php if($leadsArray['total']>0) {
                    echo "<span class='text-muted'>(" . round(($leadsArray['brak']/$leadsArray['total'])*100) . "%)</span>";
                }
            ?>
            <br />
            Отказ: <?php echo $leadsArray['otkaz'];?>
            <?php if($leadsArray['total']>0) {
                    echo "<span class='text-muted'>(" . round(($leadsArray['otkaz']/$leadsArray['total'])*100) . "%)</span>";
                }
            ?>
            <br />
        </td>
        <td>
            Заключено: <?php echo (int)$agreementsArray['counter'];?><br />
            На сумму <?php echo (int)$agreementsArray['sum'];?> руб.<br />
            Средн. 
                <?php 
                if((int)$agreementsArray['counter']) {
                    echo round(($agreementsArray['sum']/$agreementsArray['counter'])) . " руб.";
                }
                
            ?> <br /><br />
            <?php if($agreementsArray['aborted']):?>
                Расторгнуто: <?php echo (int)$agreementsArray['aborted']['counter'];?><br />
                На сумму <?php echo (int)$agreementsArray['aborted']['sum'];?> руб.<br />
            <?php endif;?>
        </td>
    </tr>
</table>
