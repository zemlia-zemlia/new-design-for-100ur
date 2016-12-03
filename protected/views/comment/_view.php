<?php
/* @var $this CommentController */
/* @var $data Comment */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('authorId')); ?>:</b>
	<?php echo CHtml::encode($data->authorId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('objectId')); ?>:</b>
	<?php echo CHtml::encode($data->objectId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('text')); ?>:</b>
	<?php echo CHtml::encode($data->text); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('dateTime')); ?>:</b>
	<?php echo CHtml::encode($data->dateTime); ?>
	<br />


</div>