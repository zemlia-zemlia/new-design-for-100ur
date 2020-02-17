<?php
/* @var $this UserStatusRequestController */
/* @var $data UserStatusRequest */
?>

<tr id="request-id-<?php echo $data->id;?>">

    <td>
        <strong><?php echo CHtml::encode($data->partner->name . ' ' . $data->partner->name2 . ' ' . $data->partner->lastName);?></strong>
        id: <?php echo CHtml::link($data->partner->id, Yii::app()->createUrl('admin/user/view', ['id' => $data->partner->id]));?><br />
        <small><i><span class="text-muted"><?php echo CustomFuncs::niceDate($data->time);?></span></i></small>

    </td>
    <td>
        <?php echo MoneyFormat::rubles($data->partner->balance);?>
    </td>
    <td>
        <?php echo MoneyFormat::rubles($data->sum);?>
    </td>
    <td>
        <p><?php echo $data->description;?></p>
        <div class="request-status-message"></div>
    </td>

    
    <td class="request-control-wrapper">
        <?php if ($data->status == TransactionCampaign::STATUS_PENDING):?>
            <select name="accountList"  id="accountList">
                <option value="">Не выбран</option>
                <?php foreach (Money::getAccountsArray() as $key => $acc):?>
                    <option value="<?= $key ?>"><?= $acc ?></option>
                <?php endforeach; ?>
            </select>
            <?php echo CHtml::link("Оплатить", "#", ['class'=>'btn btn-success btn-xs btn-block change-request-status', 'data-id'=>$data->id, 'data-action'=>'accept']);?>
        <?php else:?>
            <?php echo $data->getStatus();?>
        
        <?php endif;?>
    </td>
</tr>