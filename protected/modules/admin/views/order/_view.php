<tr>
	<td>
            <?php echo CHtml::link($data->id, Yii::app()->createUrl('admin/order/view', ['id' => $data->id]));?>
            <br />
            <span class="label label-warning">
                <?php echo $data->getStatusName();?>
            </span>
	</td>
	<td>
            <?php echo $data->author->townName;?> 
	</td>

    <td>
        <?php echo CustomFuncs::niceDate($data->createDate, false, false);?>
    </td>
    <td>
        <?php echo $data->docType->getClassName();?>.
        <?php echo $data->docType->name;?>
    </td>
	<td>
            <?php echo ($data->jurist) ? $data->jurist->getShortName() : 'нет';?> 
	</td>
    <td>
        <?php echo $data->getStatusName();?>
        <p>
        <span class="glyphicon glyphicon-comment"></span> <?php echo $data->responsesCount;?>
        </p>
    </td>
	
</tr>