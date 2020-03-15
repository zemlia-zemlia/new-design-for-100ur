<tr>
    <!-- <td><small><?php use App\helpers\DateHelper;

    echo md5($data->id); ?></small></td> -->
    <td>
        <?php echo DateHelper::niceDate($data->datetime); ?>
        <?php if (0 != $data->leadId && time() - strtotime($data->datetime) < 86400 * 3):?>
            <span class="label label-warning">холд</span>
        <?php endif; ?>
    </td>
    <td><?php echo MoneyFormat::rubles($data->sum); ?></td>
    <td><?php echo $data->comment; ?></td>
</tr>
