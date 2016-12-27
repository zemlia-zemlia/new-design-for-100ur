    <tr>
        <td>
            <?php echo CHtml::link(CHtml::encode($data->name . ' ' . $data->name2 . ' ' . $data->lastName), array('view', 'id'=>$data->id)); ?>
            <?php if($data->active==0):?>
            <span class="label label-default">неактивен</span>
            <?php endif;?>
        </td>
		<td>
			
		</td>
        <td>
            <?php echo CHtml::encode($data->email); ?>
        </td>
		<td>
            <?php echo CHtml::encode($data->phone); ?>
        </td>
        <td>
            <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/user/update',array('id'=>$data->id)), array('class'=>'btn btn-xs btn-primary'));?>
        </td>
    </tr>
