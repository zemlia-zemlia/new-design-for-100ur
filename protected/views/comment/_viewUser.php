<?php

use App\helpers\DateHelper;

$purifier = new CHtmlPurifier();
?>

<div class="row">
    <div class="col-sm-9">
        <p class="text-muted">
            <?php echo DateHelper::niceDate($data->dateTime, false, false); ?>
            &nbsp;
            <?php echo CHtml::encode($data->author->name); ?></p>
        <h3 class="text-left"><?php echo CHtml::encode($data->title); ?></h3>
        <p>
        <?php echo $purifier->purify($data->text); ?>
        </p>
    </div>
    <div class="col-sm-3 text-right">
        <p>
            Оценка: <?php echo $data->rating; ?>/5
        </p>
    </div>
</div>
<hr />