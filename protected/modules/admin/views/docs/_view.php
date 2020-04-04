<?php
/* @var $this DocsController */

use App\models\Docs;

/* @var $data Docs */
?>

<div class="view">


	<?php echo CHtml::link(CHtml::encode($data->name), ['/docs/download', 'id' => $data->id], ['target' => '_blank']); ?>(<?php echo CHtml::encode($data->downloads_count); ?>)
	<br />



</div>