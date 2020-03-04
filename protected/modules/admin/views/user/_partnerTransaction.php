<tr>
    <td><?php echo $data->id; ?></td>
    <td>
        <?php echo DateHelper::niceDate($data->datetime); ?>
        <?php if ($data->sum > 0 && time() - strtotime($data->datetime) < 86400 * 3):?>
            <span class="label label-warning">холд</span>
        <?php endif; ?>
    </td>
    <td><?php echo MoneyFormat::rubles($data->sum); ?></td>
    <td><?php echo $data->comment; ?></td>
</tr>
