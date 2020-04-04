<?php
/** @var array $campaignsArray */
/** @var array $capitalsPrices */
?>

<table class="table table-bordered table-striped">
    <tr>
        <th rowspan="2">
            Регион
        </th>
        <th class="text-left" colspan="2">
            Цена покупки
        </th>
        <th class="text-left" colspan="2">
            Цена продажи
        </th>
    </tr>
    <tr>
        <th class="text-left">
            региона
        </th>
        <th class="text-left">
            столицы
        </th>
        <th class="text-left">
            региона
        </th>
        <th class="text-left">
            столицы
        </th>
    </tr>

    <?php foreach ($campaignsArray as $regionData): ?>
        <tr>
            <td>
                <?php echo $regionData['regionName']; ?>
            </td>
            <td class="text-left">
                <div>
                    <?php
                    echo CHtml::textField('buyPrice_region_' . $regionData['regionId'], MoneyFormat::rubles($regionData['regionBuyPrice']), [
                        'class' => 'form-control region-buy-price input-sm input-xs',
                        'data-region-id' => $regionData['regionId'],
                        'style' => 'max-width:50px',
                    ]);
                    ?>
                </div>
            </td>
            <td class="text-left">
                <div>
                    <?php
                    echo CHtml::textField('buyPrice_town_' . $regionData['capitalId'], MoneyFormat::rubles($regionData['capitalBuyPrice']), [
                        'class' => 'form-control region-capital-buy-price input-sm input-xs',
                        'data-town-id' => $regionData['capitalId'],
                        'style' => 'max-width:50px',
                    ]);
                    ?>
                </div>
            </td>
            <td>
                <?php
                if ($regionData['minRegionSellPrice'] == $regionData['maxRegionSellPrice']) {
                    echo MoneyFormat::rubles($regionData['minRegionSellPrice']);
                } else {
                    echo MoneyFormat::rubles($regionData['minRegionSellPrice']) . ' - ' . MoneyFormat::rubles($regionData['maxRegionSellPrice']);
                }
                ?>
            </td>
            <td>
                <?php
                if (isset($capitalsPrices[$regionData['regionId']])) {
                    if ($capitalsPrices[$regionData['regionId']]['minCapitalSellPrice'] == $capitalsPrices[$regionData['regionId']]['maxCapitalSellPrice']) {
                        echo MoneyFormat::rubles($capitalsPrices[$regionData['regionId']]['minCapitalSellPrice']);
                    } else {
                        echo MoneyFormat::rubles($capitalsPrices[$regionData['regionId']]['minCapitalSellPrice']) . ' - ' . MoneyFormat::rubles($capitalsPrices[$regionData['regionId']]['maxCapitalSellPrice']);
                    }
                }
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>


