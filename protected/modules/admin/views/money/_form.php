<?php
/* @var $this MoneyController */

use App\models\Money;

/* @var $model Money */
/* @var $form CActiveForm */

$model->value = MoneyFormat::rubles($model->value);

$js = <<<JS
$('input[type=radio]').on('change', function() {
if($(this).attr('id') == 'App_models_Money_type_1') {
    $('#expence').removeClass('hidden');
     $('#income').addClass('hidden');
}
else{
     $('#income').removeClass('hidden');
     $('#expence').addClass('hidden');
}
});

$('#money-form').submit(function() {
  $('.sel').each(function() {
    if ($(this).hasClass('hidden')) {
        $(this).remove();
    }
  });
});
JS;

Yii::app()->clientScript->registerScript('myjquery', $js);

?>

<div class="row">
    <div class="col-md-4">
        <div class="box">
            <div class="box-body">
                <?php $form = $this->beginWidget('CActiveForm', [
                    'id' => 'money-form',
                    'enableAjaxValidation' => false,
                    'htmlOptions' => ['autocomplete' => 'off'],
                ]); ?>

                <?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>

                <div class="row">
                    <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <?php echo $form->labelEx($model, 'datetime'); ?>
                            <?php $this->widget(
                                'zii.widgets.jui.CJuiDatePicker',
                                [
                                    'name' => 'App_models_Money[datetime]',
                                    'value' => $model['datetime'],
                                    'language' => 'ru',
                                    'options' => ['dateFormat' => 'dd-mm-yy',
                                    ],
                                    'htmlOptions' => [
                                        'style' => 'text-align:right;',
                                        'class' => 'form-control',
                                    ],
                                ]
                            );
                            ?>
                            <?php echo $form->error($model, 'datetime'); ?>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <?php echo $form->labelEx($model, 'accountId'); ?>
                            <?php echo $form->dropDownList($model, 'accountId', Money::getAccountsArray(), ['class' => 'form-control']); ?>
                            <?php echo $form->error($model, 'accountId'); ?>
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <?php echo $form->labelEx($model, 'type'); ?><br>
                            <?php echo $form->radioButtonList($model, 'type', [Money::TYPE_INCOME => 'Доход', Money::TYPE_EXPENCE => 'Расход']); ?>
                            <?php echo $form->error($model, 'type'); ?>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <?php echo $form->labelEx($model, 'value'); ?>
                            <?php echo $form->textField($model, 'value', ['class' => 'form-control right-align']); ?>
                            <?php echo $form->error($model, 'value'); ?>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group <?php echo (1 == $model->type && !$model->isNewRecord) ? '' : 'hidden'; ?> sel"
                             id="expence">
                            <?php echo $form->labelEx($model, 'direction'); ?>
                            <?php echo $form->dropDownList($model, 'direction', Money::getDirectionsType()[Money::TYPE_EXPENCE], ['class' => 'form-control', 'value' => $model->direction]); ?>
                            <?php echo $form->error($model, 'direction'); ?>
                        </div>
                        <div class="form-group  <?php echo (0 == $model->type && !$model->isNewRecord) ? '' : 'hidden'; ?>  sel"
                             id="income">
                            <?php echo $form->labelEx($model, 'direction'); ?>
                            <?php echo $form->dropDownList($model, 'direction', Money::getDirectionsType()[Money::TYPE_INCOME], ['class' => 'form-control', 'value' => $model->direction]); ?>
                            <?php echo $form->error($model, 'direction'); ?>
                        </div>

                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?php echo $form->labelEx($model, 'comment'); ?>
                            <?php echo $form->textArea($model, 'comment', ['rows' => '3', 'class' => 'form-control']); ?>
                            <?php echo $form->error($model, 'comment'); ?>
                        </div>
                    </div>
                </div>

                <?php echo CHtml::submitButton('Сохранить', ['class' => 'btn btn-block btn-primary']); ?>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <?php if (!$model->isNewRecord) : ?>
            <div class="box">
                <div class="box-body">
                    <?php echo CHtml::link('Удалить запись', Yii::app()->createUrl('admin/money/delete', ['id' => $model->id]), ['class' => 'btn btn-block btn-danger', 'onclick' => 'return confirm("Удалить запись?")']); ?>
                </div>
            </div>
        <?php endif; ?>
        <?php $this->endWidget(); ?>
    </div>
</div>