<?php
/* @var $this LeadsourceController */
/* @var $data Leadsource */
?>

<tr <?php if (!$data->active) {
    echo 'class="text-muted"';
}?>>
    <td>
        <?php echo $data->id; ?>
    </td>
    <td>
	
        <?php echo CHtml::link(CHtml::encode($data->name), $this->createUrl('view', ['id' => $data->id])); ?>
        
        <?php if (1 == $data->moderation):?>
        <div><span class="label label-warning">с премодерацией</span></div>
        <?php endif; ?>
    </td>
    <td>
        <?php if ($data->user):?>
        <?php echo CHtml::link(CHtml::encode($data->user->name), Yii::app()->createUrl('/admin/user/view', ['id' => $data->user->id])); ?>
        <?php endif; ?>
    </td>

    <td>
	<?php echo CHtml::link('Редактировать', Yii::app()->createUrl('admin/leadsource/update', ['id' => $data->id])); ?> &nbsp;&nbsp;
    </td>

</tr>