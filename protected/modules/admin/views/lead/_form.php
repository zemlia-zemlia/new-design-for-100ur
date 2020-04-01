<?php
/* @var $this LeadController */

use App\models\Lead;
use App\models\Leadsource;
use App\models\User;

/* @var $model Lead */
/* @var $form CActiveForm */

$model->buyPrice = MoneyFormat::rubles($model->buyPrice);
?>

<div class="new-lead-form">
    <?php
    $form = $this->beginWidget('CActiveForm', [
        'id' => 'lead-form',
        'enableAjaxValidation' => false,
        'action' => ('' != $action) ? $action : '',
    ]);
    ?>
    <div class="row">
        <div class="col-md-4">
            <div class="box">
                <div class="box-body">
                    <?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'name'); ?>
                        <?php echo $form->textField($model, 'name', ['size' => 60, 'maxlength' => 255, 'class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'name'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'phone'); ?>
                        <?php echo $form->textField($model, 'phone', ['size' => 60, 'maxlength' => 255, 'class' => 'form-control field-phone']); ?>
                        <?php echo $form->error($model, 'phone'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->hiddenField($model, 'sourceId', ['value' => ($model->isNewRecord) ? Yii::app()->params['100yuristovSourceId'] : $model->sourceId]); ?>
                    </div>

                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'sourceId'); ?>
                                <?php echo $form->dropDownList($model, 'sourceId', Leadsource::getSourcesArray(false), ['class' => 'form-control']); ?>
                                <?php echo $form->error($model, 'sourceId'); ?>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <?php echo $form->labelEx($model, 'buyPrice'); ?>
                                <?php echo $form->textField($model, 'buyPrice', ['class' => 'form-control right-align']); ?>
                                <?php echo $form->error($model, 'buyPrice'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'question'); ?>
                        <?php echo $form->textArea($model, 'question', ['rows' => 6, 'class' => 'form-control']); ?>
                        <?php echo $form->error($model, 'question'); ?>
                    </div>

                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'town'); ?>
                        <?php
                        echo CHtml::textField('town', $model->town->name, [
                            'id' => 'town-selector',
                            'class' => 'form-control',
                        ]);
                        ?>
                        <?php
                        echo $form->hiddenField($model, 'townId', ['id' => 'selected-town']);
                        ?>
                        <?php echo $form->error($model, 'townId'); ?>
                    </div>

                    <?php if (!$model->isNewRecord): ?>
                        <div class="form-group">
                            <?php echo $form->labelEx($model, 'leadStatus'); ?>
                            <?php echo $form->dropDownList($model, 'leadStatus', Lead::getLeadStatusesArray(), ['class' => 'form-control']); ?>
                            <?php echo $form->error($model, 'leadStatus'); ?>
                        </div>
                    <?php else: ?>
                        <?php echo $form->hiddenField($model, 'leadStatus', ['value' => Lead::LEAD_STATUS_DEFAULT]); ?>
                    <?php endif; ?>

                    <?php if (!$model->isNewRecord): ?>
                        <div class="form-group">
                            <?php echo $form->labelEx($model, 'type'); ?>
                            <?php echo $form->dropDownList($model, 'type', Lead::getLeadTypesArray(), ['class' => 'form-control']); ?>
                            <?php echo $form->error($model, 'type'); ?>
                        </div>
                    <?php else: ?>
                        <?php echo $form->hiddenField($model, 'type', ['value' => Lead::TYPE_INCOMING_CALL]); ?>
                    <?php endif; ?>

                    <?php if ($model->isNewRecord && Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                        <label>
                            <?php echo $form->checkBox($model, 'testMode', []); ?>
                            <?php echo $model->getAttributeLabel('testMode'); ?>
                        </label>
                        <p class="text-muted">
                            <small>
                                В тестовом режиме лид не сохраняется в базу, а отправляется через API с выводом
                                информации об обработке
                            </small>
                        </p>
                    <?php endif; ?>

                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-primary btn-block']); ?>


                </div>
            </div>
        </div>
        <div class="col-md-4">
            <?php if (!$model->isNewRecord): ?>
                <div class="box">
                    <div class="box-header">
                        <div class="box-title">Категории права</div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <?php echo $form->checkBoxList($model, 'categoriesId', $allDirections, ['class' => '']); ?>
                            <?php echo $form->error($model, 'categories'); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->