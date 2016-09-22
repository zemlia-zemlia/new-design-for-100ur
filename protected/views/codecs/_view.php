<?php
/* @var $this CodecsController */
/* @var $data Codecs */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pagetitle')); ?>:</b>
	<?php echo CHtml::encode($data->pagetitle); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('longtitle')); ?>:</b>
	<?php echo CHtml::encode($data->longtitle); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alias')); ?>:</b>
	<?php echo CHtml::encode($data->alias); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent')); ?>:</b>
	<?php echo CHtml::encode($data->parent); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('isfolder')); ?>:</b>
	<?php echo CHtml::encode($data->isfolder); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('introtext')); ?>:</b>
	<?php echo CHtml::encode($data->introtext); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content')); ?>:</b>
	<?php echo CHtml::encode($data->content); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('menutitle')); ?>:</b>
	<?php echo CHtml::encode($data->menutitle); ?>
	<br />

	*/ ?>

</div>