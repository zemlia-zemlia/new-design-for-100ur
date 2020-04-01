<?php
/* @var $this CampaignController */

use App\models\Campaign;

/* @var $data Campaign */
$leadsSentPercent = ($data->leadsDayLimit > 0) ? ($data->leadsTodayCount / $data->leadsDayLimit) * 100 : 0;
?>

<tr <?php if (0 == $data->active) {
    echo 'class="active"';
}?>>
    <td>
        <?php echo CHtml::link($data->id, Yii::app()->createUrl('/buyer/buyer/campaign', ['id' => $data->id])); ?>
        <?php if (0 == $data->active):?>
        <br /><small><span class='label label-default'>неакт</span></small>
        <?php endif; ?>
    </td>
    <td>
        <?php echo $data->region->name; ?>
        <?php echo $data->town->name; ?>
    </td> 
    <td>
        <?php echo $data->timeFrom . '&nbsp;-&nbsp;' . $data->timeTo; ?>
    </td>
    <td>
        <?php echo $data->brakPercent; ?>
    </td>
    <td>
        <?php echo $data->leadsDayLimit; ?>
    </td>
    
    <td><?php echo MoneyFormat::rubles($data->price); ?> руб.</td>
    <td>
        <p><span class="balance-<?php echo $data->id; ?>">
        <?php echo MoneyFormat::rubles($data->balance); ?></span> руб.<br />
        </p>
        
        <?php echo CHtml::link('Транзакции', Yii::app()->createUrl('/buyer/buyer/campaign', ['id' => $data->id])); ?>
        
    </td>
    
    <td>
        <abbr title='Всего'><?php echo $data->leadsCount; ?></abbr><br />
        Сегодня:
        
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $leadsSentPercent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $leadsSentPercent; ?>%;">
              <?php echo $data->leadsTodayCount; ?> / <?php echo $data->leadsDayLimit; ?>
            </div>
          </div>
        
        
    </td>
</tr>