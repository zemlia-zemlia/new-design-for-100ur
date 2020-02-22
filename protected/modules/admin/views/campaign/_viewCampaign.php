<?php
/* @var $this CampaignController */
/* @var $data User */
?>


<?php
$leadsSentPercent = ($data->leadsDayLimit > 0) ? ($data->leadsTodayCount / $data->leadsDayLimit) * 100 : 0;
?>
<tr>
    <td>        
        <?php echo CHtml::link(CHtml::encode($data->region->name . ' ' . $data->town->name), Yii::app()->createUrl('/admin/campaign/view', ['id' => $data->id])); ?>
       
        <?php if (0 == $data->active):?>
        <small><span class='label label-default'>неакт</span></small>
        <?php endif; ?>
    </td> 
    <td>
        <?php echo CHtml::link(CHtml::encode($data->buyer->name), Yii::app()->createUrl('admin/user/view', ['id' => $data->buyer->id])); ?>
    <td>
        <?php echo $data->timeFrom . '&nbsp;-&nbsp;' . $data->timeTo; ?>
    </td>
    <td>
        <?php echo $data->brakPercent; ?>
    </td>

    
    <td><?php echo $data->price; ?> руб.</td>
	
    <td>
        <abbr title='Всего'><?php echo $data->leadsCount; ?></abbr> / 
        <abbr title='Сегодня'><?php echo $data->leadsTodayCount; ?></abbr> / 
        <abbr title='Лимит'><?php echo $data->leadsDayLimit; ?></abbr>
    </td>
</tr>
