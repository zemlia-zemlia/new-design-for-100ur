<?php
/* @var $this LeadController */
/* @var $model Lead100 */
/* @var $form CActiveForm */
?>

<div class="new-lead-form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'lead-form',
        'enableAjaxValidation' => false,
        'action' => ($action != '') ? $action : '',
    ));
    ?>
    <div class="row">
        <div class="col-md-8">
                <?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'name'); ?>
                <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'phone'); ?>
                <?php echo $form->textField($model, 'phone', array('size' => 60, 'maxlength' => 255, 'class' => 'form-control field-phone')); ?>
                <?php echo $form->error($model, 'phone'); ?>
            </div>

            <div class="form-group">
            <?php echo $form->hiddenField($model, 'sourceId', array('value' => ($model->isNewRecord) ? Yii::app()->params['100yuristovSourceId'] : $model->sourceId)); ?>
            </div>

<?php // if(!$model->isNewRecord): ?>
            <div class='row'>
                <div class='col-md-6'>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'sourceId'); ?>
                        <?php echo $form->dropDownList($model, 'sourceId', Leadsource::getSourcesArray(false), array('class' => 'form-control')); ?>
                        <?php echo $form->error($model, 'sourceId'); ?>
                    </div>
                </div>
                <div class='col-md-6'>
                    <div class="form-group">
                        <?php echo $form->labelEx($model, 'buyPrice'); ?>
                        <?php echo $form->textField($model, 'buyPrice', array('class' => 'form-control right-align')); ?>
                        <?php echo $form->error($model, 'buyPrice'); ?>
                    </div>
                </div>
            </div>
                <?php // endif; ?>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'question'); ?>
                <?php echo $form->textArea($model, 'question', array('rows' => 6, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'question'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'town'); ?>
                <?php
                echo CHtml::textField('town', $model->town->name, array(
                    'id' => 'town-selector',
                    'class' => 'form-control',
                ));
                ?>
                <?php
                    echo $form->hiddenField($model, 'townId', array('id' => 'selected-town'));
                ?>
                <?php echo $form->error($model, 'townId'); ?>
            </div>

                <?php if (!$model->isNewRecord): ?>
                <div class="form-group">
                <?php echo $form->labelEx($model, 'leadStatus'); ?>
                <?php echo $form->dropDownList($model, 'leadStatus', Lead100::getLeadStatusesArray(), array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'leadStatus'); ?>
                </div>
            <?php else: ?>
                    <?php echo $form->hiddenField($model, 'leadStatus', array('value' => Lead100::LEAD_STATUS_DEFAULT)); ?>            
                <?php endif; ?>

                <?php if (!$model->isNewRecord): ?>
                <div class="form-group">
                <?php echo $form->labelEx($model, 'type'); ?>
                <?php echo $form->dropDownList($model, 'type', Lead100::getLeadTypesArray(), array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'type'); ?>
                </div>
            <?php else: ?>
                    <?php echo $form->hiddenField($model, 'type', array('value' => Lead100::TYPE_INCOMING_CALL)); ?>            
                <?php endif; ?>

<?php if ($model->isNewRecord && Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>
                <label>
                    <?php echo $form->checkBox($model, 'testMode', array()); ?>
                    <?php echo $model->getAttributeLabel('testMode'); ?>
                </label>
                <p class="text-muted">
                    <small>
                        В тестовом режиме лид не сохраняется в базу, а отправляется через API с выводом информации об обработке
                    </small>
                </p>
<?php endif; ?>

            <?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class' => 'btn btn-primary btn-block')); ?>


        </div>
        <div class="col-md-4">
                <?php if (!$model->isNewRecord): ?>
                <div class="form-group">
                    <h3>Категории права</h3>
                    <?php echo $form->checkBoxList($model, 'categoriesId', $allDirections, array('class' => '')); ?>
                    <?php echo $form->error($model, 'categories'); ?>
                </div>
    <?php endif; ?>
        </div>
    </div>

<?php $this->endWidget(); ?>
</div><!-- form -->