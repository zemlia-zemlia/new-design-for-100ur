<tr>
    <td><?php echo $data->date;?></td>
    <td><?php echo $data->getTypeName();?></td>
    <td class="right-align"><?php echo $data->expences;?></td>
    <td><small><?php echo $data->comment;?></small></td>
    <td><?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/expence/update', array('id' => $data->id)));?></td>
</tr>