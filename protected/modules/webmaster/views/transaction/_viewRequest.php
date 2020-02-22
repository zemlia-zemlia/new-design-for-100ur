<tr>
    <td>
        <?php echo CustomFuncs::niceDate($data->datetime); ?>
    </td>
    <td><?php echo MoneyFormat::rubles($data->sum); ?></td>
    <td><?php echo $data->comment; ?></td>
    <td><?php echo $data->getStatus(); ?></td>
</tr>
