<?php
/* @var $this LeadsourceController */
/* @var $data Leadsource */
?>

<tr <?php if(!$data->active) echo 'class="muted"';?>>
    <td>
	<?php echo CHtml::encode($data->name); ?>
    </td>
    <td>
	<?php echo CHtml::encode($data->description); ?>
    </td>
    <td>
	<?php echo CHtml::link('Изменить', Yii::app()->createUrl('admin/leadsource/update', array('id'=>$data->id))); ?> &nbsp;&nbsp;
        <?php echo CHtml::link('Удалить', Yii::app()->createUrl('admin/leadsource/delete', array('id'=>$data->id))); ?>
    </td>

</tr>