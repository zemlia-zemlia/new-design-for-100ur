<tr>
    <td>
        <?php echo DateHelper::niceDate($data->createDate, false, false);?>
    </td>
    <td>
        <?php echo $data->docType->getClassName();?>.
        <?php echo $data->docType->name;?>
        <?php echo CHtml::link('Подробнее', Yii::app()->createUrl('order/view', ['id' => $data->id]));?>

    </td>
    <td>
        <?php echo $data->getStatusName();?>
        <p>
        <span class="glyphicon glyphicon-comment"></span> <?php echo $data->responsesCount;?>
        </p>
    </td>
</tr>