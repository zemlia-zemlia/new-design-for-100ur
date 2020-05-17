<div class="vert-margin30 flat-panel center-align inside">
    <?php $form = $this->beginWidget('CActiveForm', [
        'id' => 'report-form',
        'method' => 'GET',
        'htmlOptions' => ['class' => 'form-inline'],
        'enableAjaxValidation' => false,
        'action' => Yii::app()->createUrl('admin/money/report'),
    ]); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'date1'); ?>
        <?php $this->widget(
        'zii.widgets.jui.CJuiDatePicker',
        [
                'name' => 'App_models_Money[date1]',
                'value' => $model['date1'],
                'language' => 'ru',
                'options' => ['dateFormat' => 'dd-mm-yy',
                ],
                'htmlOptions' => [
                    'style' => 'text-align:right; width:85px;',
                    'class' => 'form-control input-sm',
                ],
            ]
    );
        ?>
        <?php echo $form->error($model, 'date1'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'date2'); ?>
        <?php $this->widget(
            'zii.widgets.jui.CJuiDatePicker',
            [
                'name' => 'App_models_Money[date2]',
                'value' => $model['date2'],
                'language' => 'ru',
                'options' => ['dateFormat' => 'dd-mm-yy',
                ],
                'htmlOptions' => [
                    'style' => 'text-align:right;  width:85px;',
                    'class' => 'form-control input-sm',
                ],
            ]
        );
        ?>
        <?php echo $form->error($model, 'date2'); ?>
    </div>

    <div class="form-group buttons left-align">
        <?php echo CHtml::submitButton('Показать', ['class' => 'btn btn-primary input-sm']); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>