<tr>
    <td>
        <?php use App\helpers\DateHelper;

        echo DateHelper::niceDate($data->datetime); ?>
    </td>
    <td><?php echo MoneyFormat::rubles($data->sum); ?></td>
    <td><?php echo $data->comment; ?></td>
    <td><?php echo $data->getStatus(); ?></td>
</tr>
