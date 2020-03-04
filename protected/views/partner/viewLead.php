<?php
/* @var $this ContactController */
/* @var $model Contact */

$this->setPageTitle('Лид #' . CHtml::encode($model->name) . '. ' . Yii::app()->name);

$this->breadcrumbs = [
    'Кабинет' => ['/buyer'],
        'Кампания' => ['/buyer/buyer/campaign', 'id' => $model->campaign->id],
        'Лиды' => ['/buyer/buyer/leads', 'campaign' => $model->campaign->id],
    CHtml::encode($model->name),
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/buyer/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);

?>

<h1><?php echo CHtml::encode($model->name); ?></h1>

<div class="panel panel-default">
    <div class="panel-body">
        <table class="table table-bordered">
            <tr>
                <td><strong><?php echo $model->getAttributeLabel('id'); ?></strong></td>
                <td><?php echo $model->id; ?></td>
            </tr>

            <tr>
                <td><strong><?php echo $model->getAttributeLabel('leadStatus'); ?></strong></td>
                <td><?php echo $model->getLeadStatusName(); ?></td>
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

            <tr>
                <td><strong>Дата</strong></td>
                <td><?php echo DateHelper::niceDate($model->question_date); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo $model->getAttributeLabel('question'); ?></strong></td>
                <td><?php echo nl2br(CHtml::encode($model->question)); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo $model->getAttributeLabel('price'); ?></strong></td>
                <td><?php echo $model->price; ?> руб.</td>
            </tr>
        </table>    

        <?php if (User::ROLE_ROOT == Yii::app()->user->role):?>
        <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/lead/update', ['id' => $model->id]), ['class' => 'btn btn-primary']); ?>

        <?php echo CHtml::link('Удалить лида', Yii::app()->createUrl('/admin/lead/delete', ['id' => $model->id]), ['class' => 'btn btn-danger']); ?>
        <?php endif; ?>


    </div>
</div>