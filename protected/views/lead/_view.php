<?php
/* @var $this ContactController */

use App\helpers\DateHelper;
use App\helpers\StringHelper;

/* @var $data Contact */
?>

<div class="row" id="lead-<?php echo $data->id; ?>" >
    <div class="col-sm-9">
        <p>
            <span class="muted" > 
                <span class="glyphicon glyphicon-calendar"></span>&nbsp;<?php echo DateHelper::niceDate($data->question_date, false, false); ?>&nbsp;&nbsp;
            </span>
            &nbsp; 
            <?php if ($data->townId): ?>
                <span class="glyphicon glyphicon-map-marker"></span>
                <?php echo CHtml::encode($data->town->name); ?> (<?php echo CHtml::encode($data->town->region->name); ?>)
            <?php endif; ?>
            &nbsp;

            <span class="glyphicon glyphicon-user"></span>    
            <?php echo CHtml::link(CHtml::encode($data->name), ['lead/view', 'id' => $data->id]); ?>
        </p>

        <p>
            <?php
            $questionTextCutted = StringHelper::cutString($data->question, 300);
            ?>
            <?php echo nl2br(CHtml::encode($questionTextCutted)); ?>
            <?php
            if (mb_strlen($data->question, 'utf-8') > 300) {
                echo '...';
            }
            ?>
        </p>
    </div>
    <div class="col-sm-3 text-center">
        <?php if (!$showMy): ?>
            <?php $sellPrice = (int) $data->calculatePrices()[1]; ?>
            <?php if ($sellPrice > 0): ?>
                <div class="lead">
                    <strong><?php echo $sellPrice; ?> руб.</strong>
                </div>
                <?php
                    $buyLinkAttributes = ['class' => 'btn btn-info', 'onclick' => 'return confirm("Купить эту заявку за ' . $sellPrice . ' рублей?")'];
                    if ($sellPrice > Yii::app()->user->balance) {
                        $buyLinkAttributes['disabled'] = 'disabled';
                    }
                ?>
                <?php echo CHtml::link('Купить', Yii::app()->createUrl('lead/buy', ['id' => $data->id]), $buyLinkAttributes); ?>

                <?php if ($sellPrice > Yii::app()->user->balance): ?>
                    <div><small><?php echo CHtml::link('Пополните баланс', Yii::app()->createUrl('transaction/index')); ?></small></div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<hr />