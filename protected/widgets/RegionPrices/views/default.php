<table class="table table-bordered">
    <tr>
        <th>
            Регион
        </th>
        <th class="text-left">
            Цена лида
        </th>
    </tr>
    <?php foreach ($campaignsArray as $region => $price):?>
    <tr>
        <td>
            <?php echo $region; ?>
        </td>
        <td class="text-left">
            <?php echo $price; ?> руб.
        </td>
    </tr>
    <?php endforeach; ?>
</table>


