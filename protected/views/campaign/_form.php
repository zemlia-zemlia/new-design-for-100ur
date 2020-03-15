<?php
/* @var $this CampaignController */

use App\helpers\DateHelper;

/* @var $model Campaign */
/* @var $form CActiveForm */

$model->price = MoneyFormat::rubles($model->price);
?>

<div class="">

    <?php
    $form = $this->beginWidget('CActiveForm', [
        'id' => 'campaign-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => ['class' => 'form-horizontal'],
    ]);
    ?>

    <?php echo $form->errorSummary($model, 'Пожалуйста, исправьте ошибки'); ?>

    <?php if ($model->isNewRecord || User::ROLE_SECRETARY == Yii::app()->user->role || User::ROLE_ROOT == Yii::app()->user->role): ?>
        <p class="flat-panel inside">
            Выберите регион ИЛИ город, в котором хотите покупать лиды.<br/>
            <strong>Выбрать можно ИЛИ весь регион, ИЛИ один конкретный город.</strong> Если вы желаете выкупать несколько регионов или городов то необходимо создавать на них отдельные кампании.
        </p>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'region', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-10 col-md-6">
                <?php echo $form->dropDownList($model, 'regionId', $regions, ['class' => 'form-control']); ?>
                <?php echo $form->error($model, 'regionId'); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'town', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-10 col-md-6">
                <?php
                echo CHtml::textField('town', isset($model->town->name) ? $model->town->name : '', [
                    'id' => 'town-selector',
                    'class' => 'form-control',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'bottom',
                    'title' => 'При указании города Вы будете получать лиды только из этого города',
                ]);
                ?>
            </div>
            <?php
            echo $form->hiddenField($model, 'townId', ['id' => 'selected-town']);
            ?>
            <div class="col-md-4">
                <?php echo $form->error($model, 'townId'); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (User::ROLE_SECRETARY == Yii::app()->user->role || User::ROLE_ROOT == Yii::app()->user->role): ?>
        <p class="flat-panel inside">
            Укажите рабочие дни и время, в которое хотите получать лиды. Если хотите покупать круглосуточно, укажите с 0 до 24.<br />
            Внимание: время указывается московское!
        </p>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'timeFrom', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-10  col-md-2">    
                <div class="input-group">
                    <?php echo $form->textField($model, 'timeFrom', ['class' => 'form-control']); ?>
                    <span class="input-group-addon">ч.</span>
                </div>
            </div>
            <?php echo $form->error($model, 'timeFrom'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'timeTo', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-10 col-md-2">
                <div class="input-group">
                    <?php echo $form->textField($model, 'timeTo', ['class' => 'form-control']); ?>
                    <span class="input-group-addon">ч.</span>
                </div>
            </div>
            <?php echo $form->error($model, 'timeTo'); ?>
        </div> 

        <div class="form-group">
            <?php echo $form->labelEx($model, 'days', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-10 col-md-2">
                <?php echo $form->checkBoxList($model, 'workDays', DateHelper::getWeekDays(), ['class' => '']); ?>
            </div>
            <?php echo $form->error($model, 'days'); ?>
        </div> 
        <hr/>       

    <?php endif; ?>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'leadsDayLimit', ['class' => 'col-sm-2 control-label']); ?>
        <div class="col-sm-10 col-md-6">
            <?php if ($model->isNewRecord || Yii::app()->user->checkAccess(User::ROLE_SECRETARY)): ?>
                <?php echo $form->textField($model, 'leadsDayLimit', ['class' => 'form-control']); ?>
                <?php echo $form->error($model, 'leadsDayLimit'); ?>
            <?php else: ?>
                <strong><?php echo $model->leadsDayLimit; ?></strong>
                <span class="text-muted">
                    Для смены лимита обратитесь в техподдержку
                </span>
            <?php endif; ?>
        </div>
    </div>

    <hr/>  

    <?php if (User::ROLE_SECRETARY == Yii::app()->user->role || User::ROLE_ROOT == Yii::app()->user->role): ?>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'brakPercent', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-10 col-md-2">
                <?php echo $form->textField($model, 'brakPercent', ['class' => 'form-control']); ?>
                <?php echo $form->error($model, 'brakPercent'); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'price', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-10 col-md-2">
                <div class="input-group">
                    <?php echo $form->textField($model, 'price', ['class' => 'form-control']); ?>
                    <span class="input-group-addon">руб.</span>
                </div>
                <?php echo $form->error($model, 'price'); ?>
            </div>
        </div>

    <?php endif; ?>


    <div class="form-group">
        <?php echo $form->labelEx($model, 'active', ['class' => 'col-sm-2 control-label']); ?>
        <div class="col-sm-10 col-md-6">
            <?php if (User::ROLE_SECRETARY == Yii::app()->user->role || User::ROLE_ROOT == Yii::app()->user->role): ?>

                <?php echo $form->dropDownList($model, 'active', Campaign::getActivityStatuses(), ['class' => 'form-control']); ?>
                <?php echo $form->error($model, 'active'); ?>

            <?php else: ?>
                <?php if (Campaign::ACTIVE_MODERATION != $model->active && !$model->isNewRecord): ?>
                    <div class="checkbox">
                        <label>
                            <?php echo $form->checkBox($model, 'active'); ?>
                            <?php echo $model->getAttributeLabel('active'); ?>
                        </label>
                    </div>
                <?php else: ?>
                    <?php echo $model->getActiveStatusName(); ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
                <label>
                    <?php echo $form->checkBox($model, 'sendEmail'); ?>
                    <?php echo $model->getAttributeLabel('sendEmail'); ?>
                </label>
            </div>
        </div>
    </div>

    <?php if (Yii::app()->user->checkAccess(User::ROLE_ROOT)): ?>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        <?php echo $form->checkBox($model, 'sendToApi'); ?>
                        <?php echo $model->getAttributeLabel('sendToApi'); ?>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'apiClass', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-10 col-md-6">
                <?php echo $form->textField($model, 'apiClass', ['class' => 'form-control']); ?>
            </div>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'type', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-10">
                <?php echo $form->radioButtonList($model, 'type', Campaign::getTypes()); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Отправить на модерацию' : 'Сохранить', ['class' => 'btn btn-primary']); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->

<script type="text/javascript">
    $("#Campaign_regionId").on('change', function () {
        if ($(this).val() != 0) {
            $("#town-selector").val('').hide();
            $("#selected-town").val(0);
        } else {
            $("#town-selector").show();
        }
    })
    $("#town-selector").on('change', function () {
        if ($(this).val() != '') {
            $("#Campaign_regionId").val('Не выбран').hide();
        } else {
            $("#Campaign_regionId").show();
        }
    })
</script>