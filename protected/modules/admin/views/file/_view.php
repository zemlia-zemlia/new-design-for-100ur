<?php
/* @var $this FileController */

use App\models\File;

/* @var $data File */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), ['view', 'id' => $data->id]); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('filename')); ?>:</b>
	<?php echo CHtml::encode($data->filename); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('objectId')); ?>:</b>
	<?php echo CHtml::encode($data->objectId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('objectType')); ?>:</b>
	<?php echo CHtml::encode($data->objectType); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />


</div>