<?php
/* @var $this LeadsourceController */
/* @var $data Leadsource */
?>

<tr <?php if (!$data->active) {
    echo 'class="text-muted"';
}?>>
    <td>
        <?php echo $data->id;?>
    </td>
    <td>
	
        <?php echo CHtml::link(CHtml::encode($data->name), $this->createUrl('view', array('id'=>$data->id))); ?>
        
        <?php if ($data->moderation == 1):?>
        <div><span class="label label-warning">с премодерацией</span></div>
        <?php endif;?>
    </td>
    <td>
        <?php if ($data->user):?>
        <?php echo CHtml::link(CHtml::encode($data->user->name), Yii::app()->createUrl('/admin/user/view', array('id' => $data->user->id)));?>
        <?php endif;?>
    </td>
    <td>
	<?php echo CHtml::encode($data->description); ?>
    </td>
    <td>
	<?php echo CHtml::link('Изменить', Yii::app()->createUrl('admin/leadsource/update', array('id'=>$data->id))); ?> &nbsp;&nbsp;
        <?php echo CHtml::link('Удалить', Yii::app()->createUrl('admin/leadsource/delete', array('id'=>$data->id))); ?>
    </td>

</tr>