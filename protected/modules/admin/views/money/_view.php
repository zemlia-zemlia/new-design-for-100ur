<?php
/* @var $tdis MoneyController */
/* @var $data Money */
?>

<tr class="<?php echo (Money::TYPE_INCOME == $data->type) ? 'success' : 'danger'; ?>">
    <td>
         <small><span class="text-muted"><?php echo $data->id; ?></span></small>
    </td>
    <td class="text-nowrap">
        <?php echo DateHelper::niceDate($data->datetime, false, false); ?>
        <span class="table-links-hovered-container">
            <?php echo CHtml::link("<span class='glyphicon glyphicon-pencil'></span>", Yii::app()->createUrl('/admin/money/update', ['id' => $data->id])); ?>
        </span>
    </td>
    <td><?php echo $data->getDirection(); ?></td>
    <td class="text-nowrap"><?php echo $data->getAccount(); ?></td>
    <td class="text-nowrap">
        <?php echo (Money::TYPE_INCOME == $data->type) ? '+' : '-'; ?>
        <?php echo MoneyFormat::rubles($data->value, true); ?>
    </td>
    <td><?php echo CHtml::encode($data->comment); ?></td>
</tr>
