<?php
/* @var $this DocTypeController */

use App\models\DocType;

/* @var $data DocType */
?>

<tr>
    <td>
	<?php echo CHtml::link(CHtml::encode($data->id), ['view', 'id' => $data->id]); ?>
    </td>
    <td>
	<?php echo CHtml::encode($data->getClassName()); ?>
    </td>
    <td>
	<?php echo CHtml::link(CHtml::encode($data->name), ['view', 'id' => $data->id]); ?>
    </td>
    <td>
	<?php echo CHtml::encode($data->minPrice); ?> руб.
    </td>
</tr>