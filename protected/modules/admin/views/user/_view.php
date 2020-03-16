<?php
/* @var $this UserController */

use App\models\User;

/* @var $data User */
?>

<tr>
    <td>
        <?php echo CHtml::link(CHtml::encode($data->name . ' ' . $data->name2 . ' ' . $data->lastName), ['view', 'id' => $data->id]); ?>
        <?php if (0 == $data->active100):?>
        <span class="label label-default">неактивен</span>
        <?php endif; ?>
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
        <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('user/update', ['id' => $data->id]), ['class' => 'btn btn-primary']); ?>
    </td>
</tr>