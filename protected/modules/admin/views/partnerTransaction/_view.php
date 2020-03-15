<?php
/* @var $this UserStatusRequestController */

use App\helpers\DateHelper;

/* @var $data UserStatusRequest */
?>

<tr id="request-id-<?php echo $data->id; ?>">

    <td>
        <strong><?php echo CHtml::encode($data->partner->name . ' ' . $data->partner->name2 . ' ' . $data->partner->lastName); ?></strong>
        id: <?php echo CHtml::link($data->partner->id, Yii::app()->createUrl('admin/user/view', ['id' => $data->partner->id])); ?><br />
        <small><i><span class="text-muted"><?php echo DateHelper::niceDate($data->datetime); ?></span></i></small>

    </td>
    <td>
        <?php echo MoneyFormat::rubles($data->partner->calculateWebmasterBalance()); ?>
    </td>
    <td>
        <?php echo MoneyFormat::rubles($data->sum); ?>
    </td>
    <td>
        <p><?php echo $data->comment; ?></p>
        <div class="request-status-message"></div>
    </td>
    
    <td class="request-control-wrapper">
        <?php if (PartnerTransaction::STATUS_PENDING == $data->status):?>
            <?php echo CHtml::link('Одобрить', '#', ['class' => 'btn btn-success btn-xs btn-block change-request-status', 'data-id' => $data->id, 'data-action' => 'accept']); ?>
        <?php else:?>
            <?php echo $data->getStatus(); ?>
        
        <?php endif; ?>
    </td>
</tr>