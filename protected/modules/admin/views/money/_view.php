<?php
/* @var $tdis MoneyController */
/* @var $data Money */
?>

<tr class="<?php echo ($data->type == Money::TYPE_INCOME)?'success':'danger';?>">
    <td>
         <small><span class="text-muted"><?php echo $data->id;?></span></small>
    </td>
    <td>
        <?php echo CustomFuncs::niceDate($data->datetime, false, false);?>
        <span class="table-links-hovered-container">
            <?php echo CHtml::link("<span class='glyphicon glyphicon-pencil'></span>", Yii::app()->createUrl('/admin/money/update', array('id'=>$data->id)));?>
        </span>
    </td>
    <td><?php echo $data->getDirection();?></td>
    <td><?php echo $data->getAccount();?></td>
    <td>
        <?php echo ($data->type == Money::TYPE_INCOME)?'+':'-';?>
        <?php echo $data->value;?> руб.</td>
    <td><?php echo CHtml::encode($data->comment);?></td>
</tr>
