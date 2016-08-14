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
    <td><?php echo $data->price;?> руб.</td>
    <td><?php echo $data->balance;?> руб.</td>
    
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