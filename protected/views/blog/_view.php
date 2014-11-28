<?php
/* @var $this CategoryController */
/* @var $data Postcategory */
?>

<tr>
    <td>
        <?php echo CHtml::link(CHtml::encode($data->title), array('view', 'id'=>$data->id), array('class'=>'category-name')); ?>
    </td>
    <td>
        <?php echo sizeof($data->posts); ?>
    </td>
</tr>