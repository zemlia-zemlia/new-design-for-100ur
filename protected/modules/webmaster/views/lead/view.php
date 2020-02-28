<?php
/* @var $this ContactController */
/* @var $model Contact */

$this->setPageTitle(CHtml::encode($model->name) . ". Лиды. " . Yii::app()->name);

$this->breadcrumbs = array(
    CHtml::encode($model->name),
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('Кабинет вебмастера', "/webmaster/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));

?>

<div class="box">
    <div class="box-body">
        <h1><?php echo CHtml::encode($model->name); ?></h1>

        <table class="table table-bordered">
            <tr>
                <td><strong><?php echo $model->getAttributeLabel('id'); ?></strong></td>
                <td><?php echo $model->id; ?></td>
            </tr>
            <tr>
                <td><strong><?php echo $model->getAttributeLabel('question_date'); ?></strong></td>
                <td><?php echo CustomFuncs::niceDate($model->question_date); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo $model->getAttributeLabel('source'); ?></strong></td>
                <td><?php echo $model->source->name; ?></td>
            </tr>
            <tr>
                <td><strong><?php echo $model->getAttributeLabel('leadStatus'); ?></strong></td>
                <td>
                    <?php echo $model->getLeadStatusName(); ?>
                    <?php if ($model->leadStatus == Lead::LEAD_STATUS_NABRAK): ?>
                        <p>Причина: <?php echo $model->getReasonName(); ?></p>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><strong><?php echo $model->getAttributeLabel('phone'); ?></strong></td>
                <td>
                    <?php echo $model->phone; ?><br/>
                </td>
            </tr>
            <tr>
                <td><strong><?php echo $model->getAttributeLabel('town'); ?></strong></td>
                <td><?php echo $model->town->name; ?></td>
            </tr>
            <tr>
                <td><strong><?php echo $model->getAttributeLabel('buyPrice'); ?></strong></td>
                <td><?php echo MoneyFormat::rubles($model->buyPrice); ?> руб.</td>
            </tr>
            <tr>
                <td><strong><?php echo $model->getAttributeLabel('question'); ?></strong></td>
                <td><?php echo nl2br(CHtml::encode($model->question)); ?></td>
            </tr>
        </table>
    </div>
</div>

