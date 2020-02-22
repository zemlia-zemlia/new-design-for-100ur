<?php
/* @var $this ContactController */
/* @var $model Contact */

$this->setPageTitle(CHtml::encode($model->name) . '. Лиды. ' . Yii::app()->name);
Yii::app()->clientScript->registerScriptFile('/js/admin/lead.js');

$this->breadcrumbs = [
    'Лиды' => ['index'],
    CHtml::encode($model->name),
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>


<div class='row'>
    <div class='col-md-8'>
        <div class="box">
            <div class="box-body">
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
                            <?php if (Lead::LEAD_STATUS_SENT == $model->leadStatus && $model->buyer): ?>
                                <?php echo $model->buyer->name; ?>
                            <?php endif; ?>
                            <?php if (Lead::LEAD_STATUS_NABRAK == $model->leadStatus): ?>
                                <p>Причина: <?php echo $model->getReasonName(); ?></p>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php if (User::ROLE_JURIST != Yii::app()->user->role || $model->employeeId): ?>
                        <tr>
                            <td><strong><?php echo $model->getAttributeLabel('phone'); ?></strong></td>
                            <td>
                                <?php if ($model->phone && !(User::ROLE_JURIST == Yii::app()->user->role && $model->employeeId != Yii::app()->user->id)): ?>
                                    <?php echo $model->phone; ?><br/>
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


                    <?php if (User::ROLE_JURIST != Yii::app()->user->role): ?>
                        <tr>
                            <td><strong><?php echo $model->getAttributeLabel('source'); ?></strong></td>
                            <td><?php echo CHtml::link(CHtml::encode($model->source->name), Yii::app()->createUrl('admin/leadsource/view', ['id' => $model->source->id])); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo $model->getAttributeLabel('buyPrice'); ?></strong></td>
                            <td><?php echo MoneyFormat::rubles($model->buyPrice); ?> руб.</td>
                        </tr>
                        <tr>
                            <td><strong>Цена продажи</strong></td>
                            <td><?php echo MoneyFormat::rubles($model->price); ?> руб.</td>
                        </tr>
                    <?php endif; ?>

                    <tr>
                        <td><strong><?php echo $model->getAttributeLabel('question_date'); ?></strong></td>
                        <td><?php echo CustomFuncs::niceDate($model->question_date); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Категории</strong></td>
                        <td>
                            <ul>
                                <?php foreach ($model->categories as $cat): ?>
                                    <li><?php echo $cat->name; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php echo $model->getAttributeLabel('question'); ?></strong></td>
                        <td><?php echo nl2br(CHtml::encode($model->question)); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="box">
            <div class="box-header">
                <div class="box-title">Записи из лога</div>
            </div>
            <div class="box-body">
                <?php
                // выводим виджет с последними записями лога
                $this->widget('application.widgets.LogReader.LogReaderWidget', [
                    'class' => 'Lead',
                    'subjectId' => $model->id,
                ]);
                ?>
            </div>
        </div>

    </div>

    <div class='col-md-4'>
        <div class="box">
            <div class="box-header">
                <div class="box-title">Управление</div>
            </div>
            <div class="box-body">
                <div class="">
                    <?php if (Yii::app()->user->checkAccess(User::ROLE_SECRETARY)): ?>
                        <?php echo CHtml::link('Редактировать', Yii::app()->createUrl('/admin/lead/update', ['id' => $model->id]), ['class' => 'btn btn-block btn-primary']); ?>
                    <?php endif; ?>
                    <br/>
                    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                        <?php echo CHtml::link('Удалить', Yii::app()->createUrl('/admin/lead/delete', ['id' => $model->id]), ['class' => 'btn btn-block btn-danger']); ?>
                        <br/><br/><br/>

                        <?php if (Lead::LEAD_STATUS_BRAK != $model->leadStatus): ?>
                            <span id="lead-<?php echo $model->id; ?>">
                <?php echo CHtml::link('В брак без возврата денег покупателю', '#', ['class' => 'btn btn-block btn-default lead-change-status', 'data-id' => $model->id, 'data-status' => Lead::LEAD_STATUS_BRAK, 'data-refund' => 0]); ?>
                </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <?php if (Lead::LEAD_STATUS_DEFAULT == $model->leadStatus): ?>
                    <h4 class="vert-margin20">Ручная отправка лида в кампанию</h4>
                    <div id='force-sell'>
                        <?php foreach ($campaigns as $campaing): ?>
                            <p>
                                <?php echo CHtml::link($campaing->region->name . ' ' . $campaing->town->name, '#', ['class' => 'force-sell-lead', 'data-id' => $model->id, 'data-campaignid' => $campaing->id]); ?>
                                Покупатель: <?php echo CHtml::link($campaing->buyerId, Yii::app()->createUrl('admin/user/view', ['id' => $campaing->buyerId])); ?>
                            </p>

                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

