<?php
/* @var $this UserStatusRequestController */
/* @var $data UserStatusRequest */
?>

<tr id="request-id-<?php echo $data->id;?>">

    <td>
        <?php echo CustomFuncs::niceDate($data->datetime);?><br />
        <?php echo CHtml::encode($data->partner->name . ' ' . $data->partner->name2 . ' ' . $data->partner->lastName);?>
        <br />
        id: <?php echo CHtml::link($data->partner->id, Yii::app()->createUrl('admin/user/view', array('id' => $data->partner->id)));?>
    </td>
    <td>
        <?php echo round($data->partner->calculateWebmasterBalance(), 2);?>
    </td>
    <td>
        <?php echo $data->sum;?>
    </td>
    <td>
        <p><?php echo $data->comment;?></p>
        <div class="request-status-message"></div>
    </td>
    
    <td class="request-control-wrapper">
        <?php if($data->status == PartnerTransaction::STATUS_PENDING):?>
            <?php echo CHtml::link("Одобрить", "#", array('class'=>'btn btn-success btn-xs btn-block change-request-status', 'data-id'=>$data->id, 'data-action'=>'accept'));?>
        <?php else:?>
            <?php echo $data->getStatus();?>
        
        <?php endif;?>
    </td>
</tr>