<?php
/* @var $this TransactionCampaignController */
/* @var $data TransactionCampaign */
?>

<?php $transactionClass = ($data->sum>=0)?'success':'danger';?>

<tr>
    <td>
        <?php echo CustomFuncs::niceDate($data->time);?>
    </td>
    <td>
        <?php echo $data->campaign->town->name . ' ' . $data->campaign->region->name;?>
    </td>
    <td class="<?php echo $transactionClass;?>">
        <?php echo MoneyFormat::rubles($data->sum);?>
    </td>
    <td>
        <?php echo CHtml::encode($data->description);?>
    </td>
</tr>
