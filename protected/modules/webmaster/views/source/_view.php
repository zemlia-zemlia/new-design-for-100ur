<?php
/* @var $this LeadsourceController */
/* @var $data Leadsource */
?>

<tr <?php if (!$data->active) {
    echo 'class="text-muted"';
}?>>
    <td>
        <?php echo $data->id; ?>
    </td>
    <td>
	
        <?php echo CHtml::link(CHtml::encode($data->name), $this->createUrl('view', ['id' => $data->id])); ?>
    </td>
    <td>
	<?php echo CHtml::encode($data->description); ?>
    </td>
    <td>
	<?php echo CHtml::link('Изменить', Yii::app()->createUrl('webmaster/source/update', ['id' => $data->id])); ?> &nbsp;&nbsp;
    </td>

</tr>