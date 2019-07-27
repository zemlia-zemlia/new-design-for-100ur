<tr>
    <td>
        <?php if ($data instanceof PartnerTransaction): ?>
            <?php echo CustomFuncs::niceDate($data->datetime); ?>
            <?php if ($data->leadId != 0 && time() - strtotime($data->datetime) < 86400 * 3): ?>
                <span class="label label-warning">холд</span>
            <?php endif; ?>
        <?php elseif ($data instanceof TransactionCampaign): ?>
            <?php echo CustomFuncs::niceDate($data->time); ?>
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
