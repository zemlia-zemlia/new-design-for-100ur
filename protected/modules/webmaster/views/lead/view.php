<?php
/* @var $this ContactController */
/* @var $model Contact */

$this->setPageTitle(CHtml::encode($model->name) . '. Лиды. ' . Yii::app()->name);

$this->breadcrumbs = [
    CHtml::encode($model->name),
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('Кабинет вебмастера', '/webmaster/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);

?>

<h1><?php echo CHtml::encode($model->name); ?></h1>


<table class="table table-bordered">
    <tr>
        <td><strong><?php echo $model->getAttributeLabel('id'); ?></strong></td>
        <td><?php echo $model->id; ?></td>
    </tr>
    
    <tr>
        <td><strong><?php echo $model->getAttributeLabel('leadStatus'); ?></strong></td>
        <td>
            <?php echo $model->getLeadStatusName(); ?>
            <?php if (Lead::LEAD_STATUS_NABRAK == $model->leadStatus):?>
            <p>Причина: <?php echo $model->getReasonName(); ?></p>
            <?php endif; ?>
        </td>
    </tr>
    
    <?php if (User::ROLE_JURIST != Yii::app()->user->role || $model->employeeId):?>
    <tr>
        <td><strong><?php echo $model->getAttributeLabel('phone'); ?></strong></td>
        <td>
            <?php if ($model->phone && !(User::ROLE_JURIST == Yii::app()->user->role && $model->employeeId != Yii::app()->user->id)):?>
                <?php echo $model->phone; ?><br />
            <?php endif; ?>
        </td>
    </tr>
    
    <tr>
        <td><strong><?php echo $model->getAttributeLabel('email'); ?></strong></td>
        <td><?php echo $model->email; ?></td>
    </tr>
    <?php endif; ?>
    <tr>
        <td><strong><?php echo $model->getAttributeLabel('town'); ?></strong></td>
        <td><?php echo $model->town->name; ?></td>
    </tr>
    
    
    <?php if (User::ROLE_JURIST != Yii::app()->user->role):?>
    <tr>
        <td><strong><?php echo $model->getAttributeLabel('source'); ?></strong></td>
        <td><?php echo $model->source->name; ?></td>
    </tr>
    <tr>
        <td><strong><?php echo $model->getAttributeLabel('buyPrice'); ?></strong></td>
        <td><?php echo MoneyFormat::rubles($model->buyPrice); ?> руб.</td>
    </tr>
    <?php endif; ?>
    
    <tr>
        <td><strong><?php echo $model->getAttributeLabel('question_date'); ?></strong></td>
        <td><?php echo CustomFuncs::niceDate($model->question_date); ?></td>
    </tr>
    <tr>
        <td><strong><?php echo $model->getAttributeLabel('question'); ?></strong></td>
        <td><?php echo nl2br(CHtml::encode($model->question)); ?></td>
    </tr>
</table>    
    
<?php if (User::ROLE_ROOT == Yii::app()->user->role):?>
<?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/lead/update', ['id' => $model->id]), ['class' => 'btn btn-primary']); ?>

<?php echo CHtml::link('Удалить', Yii::app()->createUrl('/admin/lead/delete', ['id' => $model->id]), ['class' => 'btn btn-danger']); ?>
<?php endif; ?>


