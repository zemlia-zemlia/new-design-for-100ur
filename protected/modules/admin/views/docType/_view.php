<?php
/* @var $this DocTypeController */
/* @var $data DocType */
?>

<tr>
    <td>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
    </td>
    <td>
	<?php echo CHtml::encode($data->getClassName()); ?>
    </td>
    <td>
	<?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?>
    </td>
    <td>
	<?php echo CHtml::encode($data->minPrice); ?> руб.
    </td>
</tr>