<?php
/* @var $this CampaignController */
/* @var $data Campaign */
$leadsSentPercent = ($data->leadsDayLimit>0)?($data->leadsTodayCount/$data->leadsDayLimit)*100:0;
?>

<tr <?php if($data->active==0){echo 'class="active"';}?>>
    <td>
        <?php echo CHtml::link($data->id, Yii::app()->createUrl('/admin/campaign/view', array('id'=>$data->id)));?>
        <?php if($data->active==0):?>
        <br /><small><span class='label label-default'>неакт</span></small>
        <?php endif;?>
    </td>
    <td>
        <p>
            <?php echo CHtml::link(CHtml::encode($data->buyer->lastName . ' ' . $data->buyer->name), Yii::app()->createUrl('/admin/user/view', array('id'=>$data->buyer->id)));?>
        </p>
        <?php echo $data->buyer->email;?></td>
    <td>
        <?php echo $data->region->name;?>
        <?php echo $data->town->name;?>
    </td> 
    <td>
        <?php echo $data->timeFrom . '&nbsp;-&nbsp;' . $data->timeTo;?>
    </td>
    <td>
        <?php echo $data->brakPercent;?>
    </td>
    <td>
        <?php echo $data->leadsDayLimit;?>
    </td>
    
    <td><?php echo $data->price;?> руб.</td>
    <td>
        <h4><span class="label label-default balance-<?php echo $data->id;?>">
        <?php echo $data->balance;?> руб.</span></h4>

    </td>
	
    <td>
	<div class="campaign-topup-message"></div>
        <a href="#" class="campaign-topup btn btn-block btn-xs btn-default" data-id="<?php echo $data->id;?>">Пополнить</a>
        
        <form id="campaign-<?php echo $data->id;?>" data-id="<?php echo $data->id;?>" class="form-inline form-campaign-topup">
            <div class="form-group">
                <input type="text" name="sum" style="width:70px" class="form-control input-sm" placeholder="Сумма" />
            </div>
            
            <a href="#" class="btn  btn-primary btn-sm submit-topup">+</a>
            <br />
            <a href="#" class="campaign-topup-close">Отмена</a>
        </form>
	</td>
	
    <td>
        <abbr title='Всего'><?php echo $data->leadsCount;?></abbr><br />
        Сегодня:
        
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $leadsSentPercent;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $leadsSentPercent;?>%;">
              <?php echo $data->leadsTodayCount;?> / <?php echo $data->leadsDayLimit;?>
            </div>
          </div>
        
        
    </td>
</tr>