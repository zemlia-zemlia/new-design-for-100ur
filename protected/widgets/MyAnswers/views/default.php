<?php
    $answersTotalString = str_split(str_pad((string)$answersTotal,3, '0',STR_PAD_LEFT));
    $answersMonthString = str_split(str_pad((string)$answersMonth,3, '0',STR_PAD_LEFT));
    $answersDayString = str_split(str_pad((string)$answersDay,3, '0',STR_PAD_LEFT));
?>


<h4 class="header-block header-block-light-grey">Счетчики ваших ответов</h4>

<div class="row" id="my-answers-counters">
    <div class="col-md-4">
        <div class="center-align"><small>Всего</small></div>
        <p class="kpi-counter center-align">
            <?php foreach($answersTotalString as $num):?><span><?php echo $num;?></span><?php endforeach;?>
        </p>

    </div>
    <div class="col-md-4">
        <div class="center-align"><small>За 30 дней</small></div>
        <p class="kpi-counter center-align">
            <?php foreach($answersMonthString as $num):?><span><?php echo $num;?></span><?php endforeach;?>
        </p>
    </div>
    <div class="col-md-4">
        <div class="center-align"><small>Сегодня</small></div>
        <p class="kpi-counter center-align">
            <?php foreach($answersDayString as $num):?><span><?php echo $num;?></span><?php endforeach;?>
        </p>
    </div>
</div>