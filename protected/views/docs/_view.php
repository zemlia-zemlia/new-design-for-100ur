<?php
/* @var $this DocsController */
/* @var $data Docs */
?>

<div class="view">


	<?php echo CHtml::link(CHtml::encode($data->name), '/docs/download/?id='.$data->id, ['target' => '_blank']); ?>(<?php echo CHtml::encode($data->downloads_count); ?>)
	<br />



</div>