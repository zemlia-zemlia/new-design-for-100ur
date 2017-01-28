<?php
/* @var $this UserStatusRequestController */
/* @var $data UserStatusRequest */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('yuristId')); ?>:</b>
	<?php echo CHtml::encode($data->yuristId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isVerified')); ?>:</b>
	<?php echo CHtml::encode($data->isVerified); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vuz')); ?>:</b>
	<?php echo CHtml::encode($data->vuz); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('facultet')); ?>:</b>
	<?php echo CHtml::encode($data->facultet); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('education')); ?>:</b>
	<?php echo CHtml::encode($data->education); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('vuzTownId')); ?>:</b>
	<?php echo CHtml::encode($data->vuzTownId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('educationYear')); ?>:</b>
	<?php echo CHtml::encode($data->educationYear); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('advOrganisation')); ?>:</b>
	<?php echo CHtml::encode($data->advOrganisation); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('advNumber')); ?>:</b>
	<?php echo CHtml::encode($data->advNumber); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('position')); ?>:</b>
	<?php echo CHtml::encode($data->position); ?>
	<br />

	*/ ?>

</div>