<?php
/* @var $this ContactController */
/* @var $model Contact */

$this->setPageTitle("Лид #" . CHtml::encode($model->name) . '. '. Yii::app()->name);

$this->breadcrumbs=array(
    'Кабинет'   =>  array('/buyer'),
        'Кампания'  =>  array('/buyer/campaign', 'id'=>$model->campaign->id),
        'Лиды'      =>  array('/buyer/leads', 'campaign'=>$model->campaign->id),
    CHtml::encode($model->name),
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов', "/buyer/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));

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

            <?php if (Yii::app()->user->role != User::ROLE_JURIST || $model->employeeId):?>
            <tr>
                <td><strong><?php echo $model->getAttributeLabel('phone'); ?></strong></td>
                <td>
                    <?php if ($model->phone && !(Yii::app()->user->role == User::ROLE_JURIST && $model->employeeId != Yii::app()->user->id)):?>
                        <?php echo $model->phone; ?><br />
                    <?php endif;?>
                </td>
            </tr>

            <tr>
                <td><strong><?php echo $model->getAttributeLabel('email'); ?></strong></td>
                <td><?php echo $model->email; ?></td>
            </tr>
            <?php endif;?>
            <tr>
                <td><strong><?php echo $model->getAttributeLabel('town'); ?></strong></td>
                <td><?php echo $model->town->name; ?></td>
            </tr>

            <tr>
                <td><strong>Дата</strong></td>
                <td><?php echo CustomFuncs::niceDate($model->question_date); ?></td>
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

        <?php if (Yii::app()->user->role == User::ROLE_ROOT):?>
        <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/lead/update', array('id'=>$model->id)), array('class'=>'btn btn-primary'));?>

        <?php echo CHtml::link('Удалить лида', Yii::app()->createUrl('/admin/lead/delete', array('id'=>$model->id)), array('class'=>'btn btn-danger'));?>
        <?php endif; ?>


    </div>
</div>