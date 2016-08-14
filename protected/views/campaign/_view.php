<?php
/* @var $this CampaignController */
/* @var $data Campaign */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('regionId')); ?>:</b>
	<?php echo CHtml::encode($data->regionId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('townId')); ?>:</b>
	<?php echo CHtml::encode($data->townId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('timeFrom')); ?>:</b>
	<?php echo CHtml::encode($data->timeFrom); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('timeTo')); ?>:</b>
	<?php echo CHtml::encode($data->timeTo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode($data->price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('balance')); ?>:</b>
	<?php echo CHtml::encode($data->balance); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('leadsDayLimit')); ?>:</b>
	<?php echo CHtml::encode($data->leadsDayLimit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('brakPercent')); ?>:</b>
	<?php echo CHtml::encode($data->brakPercent); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('buyerId')); ?>:</b>
	<?php echo CHtml::encode($data->buyerId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('active')); ?>:</b>
	<?php echo CHtml::encode($data->active); ?>
	<br />

	*/ ?>

</div>