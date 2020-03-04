<?php
/* @var $this CampaignController */
/* @var $data User */
?>
<?php if (true == $showInactive || $data->campaignsActiveCount > 0):?>
<tr class="active">
    <td colspan="">
        <?php echo CHtml::link(CHtml::encode($data->lastName . ' ' . $data->name), Yii::app()->createUrl('/admin/user/view', ['id' => $data->id])); ?> 
        id: <?php echo $data->id; ?>
    </td>
	<td colspan="5">
		<span class="label label-default balance-<?php echo $data->id; ?>">
        <?php echo $data->balance; ?> руб.</span>
            
        <div class="buyer-topup-message"></div>
        <a href="#" class="buyer-topup btn btn-xs btn-default" data-id="<?php echo $data->id; ?>">Пополнить</a>
        
        <form id="buyer-<?php echo $data->id; ?>" data-id="<?php echo $data->id; ?>" class="form-inline form-buyer-topup">
            <div class="form-group">
                <input type="text" name="sum" style="width:70px" class="form-control input-sm" placeholder="Сумма" />
            </div>
            
            <a href="#" class="btn  btn-primary btn-sm submit-topup">+</a>
            <br />
            <a href="#" class="buyer-topup-close">Отмена</a>
        </form>
	</td>
</tr>

<?php foreach ($data->campaigns as $campaign):?>
<?php if (true == $showInactive || 1 == $campaign->active):?>
<?php
$leadsSentPercent = ($campaign->leadsDayLimit > 0) ? ($campaign->leadsTodayCount / $campaign->leadsDayLimit) * 100 : 0;
?>
<tr>
    <td style="width:200px;">
    </td>
    <td>        
        <?php echo CHtml::link(CHtml::encode($campaign->region->name . ' ' . $campaign->town->name), Yii::app()->createUrl('/admin/campaign/view', ['id' => $campaign->id])); ?>
       
        <?php if (0 == $campaign->active):?>
        <small><span class='label label-default'>неакт</span></small>
        <?php endif; ?>
    </td> 
    <td>
        <?php echo $campaign->timeFrom . '&nbsp;-&nbsp;' . $campaign->timeTo; ?>
    </td>
    <td>
        <?php echo $campaign->brakPercent; ?>
    </td>
    
    <td><?php echo $campaign->price; ?> руб.</td>
	
    <td>
        <abbr title='Всего'><?php echo $campaign->leadsCount; ?></abbr> / 
        <abbr title='Сегодня'><?php echo $campaign->leadsTodayCount; ?></abbr> / 
        <abbr title='Лимит'><?php echo $campaign->leadsDayLimit; ?></abbr>
    </td>
</tr>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>