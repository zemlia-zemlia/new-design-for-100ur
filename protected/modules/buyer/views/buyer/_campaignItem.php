<table class="table table-bordered">
    <thead>
    <tr>
        <th>регион</th>
        <th>статус</th>
        <th>цена</th>
        <th>кол-во</th>
        <th></th>
    </tr>
    </thead>
    <?php foreach ($campaigns as $campaign): ?>

        <tr>
            <td>
                <h5><?php echo CHtml::link($campaign->region->name . ' ' . $campaign->town->name,
                        Yii::app()->createUrl('/buyer/buyer/leads', ['campaign' => $campaign->id])); ?></h5>
            </td>
            <td>
                <?php echo $campaign->getActiveStatusName(); ?>
            </td>
            <td>
                <?php echo MoneyFormat::rubles($campaign->price); ?> руб.
            </td>
            <td>
                <?php echo $campaign->leadsDayLimit; ?>
            </td>
            <td>
                <?php echo CHtml::link("Настройки <span class='glyphicon glyphicon-cog'></span>",
                    Yii::app()->createUrl('/buyer/buyer/campaign', ['id' => $campaign->id])); ?>
            </td>
        </tr>

    <?php endforeach; ?>
</table>