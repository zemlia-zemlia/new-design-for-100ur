<?php
/* @var $this TransactionCampaignController */

use App\helpers\DateHelper;
use App\models\TransactionCampaign;

/* @var $data TransactionCampaign */
?>

<?php $transactionClass = ($data->sum >= 0) ? 'success' : 'danger'; ?>

<tr>
    <td>
        <?php echo DateHelper::niceDate($data->time); ?>
    </td>
    <td>
        <?php echo $data->campaign->town->name . ' ' . $data->campaign->region->name; ?>
    </td>
    <td class="<?php echo $transactionClass; ?>">
        <?php echo MoneyFormat::rubles($data->sum); ?>
    </td>
    <td>
        <?php echo CHtml::encode($data->description); ?>
    </td>
</tr>
