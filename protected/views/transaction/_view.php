<tr>
    <td>
        <?php use App\helpers\DateHelper;

        if ($data instanceof PartnerTransaction): ?>
            <?php echo DateHelper::niceDate($data->datetime); ?>
            <?php if (0 != $data->leadId && time() - strtotime($data->datetime) < 86400 * 3): ?>
                <span class="label label-warning">холд</span>
            <?php endif; ?>
        <?php elseif ($data instanceof TransactionCampaign): ?>
            <?php echo DateHelper::niceDate($data->time); ?>
        <?php endif; ?>
    </td>
    <td><?php echo MoneyFormat::rubles($data->sum); ?></td>
    <td>
        <?php if ($data instanceof PartnerTransaction): ?>    
            <?php echo $data->comment; ?>
        <?php elseif ($data instanceof TransactionCampaign): ?>
            <?php echo $data->description; ?>
        <?php endif; ?>
    </td>
</tr>
