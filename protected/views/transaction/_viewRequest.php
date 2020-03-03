<tr>
    <td>
        <?php echo DateHelper::niceDate(isset($data->datetime) ? $data->datetime : $data->time); ?>
    </td>
    <td><?php echo MoneyFormat::rubles($data->sum); ?></td>
    <td><?php echo isset($data->comment) ? $data->comment : $data->description; ?></td>
    <td><?php echo $data->getStatus(); ?></td>
</tr>
