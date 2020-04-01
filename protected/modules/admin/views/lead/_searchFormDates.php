<div class="vert-margin30 flat-panel center-align inside">
<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'lead-search-form',
        'method' => 'GET',
        'htmlOptions' => ['class' => 'form-inline'],
    'enableAjaxValidation' => false,
        'action' => $action,
]); ?>

<div class="form-group">
                <?php echo $form->labelEx($model, 'date1'); ?>
                <?php $this->widget(
    'zii.widgets.jui.CJuiDatePicker',
    [
                        'name' => 'App\models\Lead[date1]',
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
                        'name' => 'App\models\Lead[date2]',
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
    <?php echo CHtml::submitButton('Найти', ['class' => 'btn btn-primary input-sm']); ?>
</div>

<?php $this->endWidget(); ?>
</div>