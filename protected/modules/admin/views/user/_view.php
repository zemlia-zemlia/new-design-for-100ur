<?php
/* @var $this UserController */
/* @var $data User */
?>

<tr>
    <td>
        <?php echo CHtml::link(CHtml::encode($data->name . ' ' . $data->name2 . ' ' . $data->lastName), array('view', 'id'=>$data->id)); ?>
        <?php if ($data->active100==0):?>
        <span class="label label-default">неактивен</span>
        <?php endif;?>
        <div class="muted">
            <?php echo CHtml::encode($data->position); ?>
        </div>
        <?php echo $data->getRoleName(); ?>
    </td>
    <td>
        <?php echo CHtml::encode($data->email); ?><br />
        <?php echo CHtml::encode($data->phone); ?>
    </td>
    <td>
        <?php echo CHtml::encode($data->office->name); ?><br />
        <?php echo CHtml::encode($data->manager->name . ' ' . $data->manager->lastName); ?>
    </td>
    <td>
        <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('user/update', array('id'=>$data->id)), array('class'=>'btn btn-primary'));?>
    </td>
</tr>