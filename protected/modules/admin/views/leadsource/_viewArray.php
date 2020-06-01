<?php
/** @var array $sources */
?>

<?php foreach ($sources as $source): ?>
    <tr>
        <td><?php echo $source['id']; ?></td>
        <td>
            <?php echo CHtml::link($source['name'], Yii::app()->createUrl('/admin/leadsource/view', ['id' => $source['id']])); ?>
        </td>
        <td>
            <?php echo CHtml::link($source['user_name'], Yii::app()->createUrl('/admin/user/view', ['id' => $source['user_id']])); ?>
        </td>
        <td>
            <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('admin/leadsource/update', ['id' => $source['id']])); ?>
        </td>
    </tr>
<?php endforeach; ?>
