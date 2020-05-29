    <div class="row">
        <?php

        use App\models\Lead;
        use App\models\Leadsource;
        use App\models\Region;
        use App\models\Town;

        $form = $this->beginWidget('CActiveForm', [
            'id' => 'lead-search-form',
            'method' => 'GET',
            'action' => Yii::app()->createUrl('admin/lead/index'),
            'htmlOptions' => ['class' => 'form-inline', 'autocomplete' => 'off'],
            'enableAjaxValidation' => false,
        ]);
        ?>

        <?php
        $townName = ($model->townId) ? Town::getName($model->townId) : '';
        ?>
        <div class="col-sm-1 col-xs-2">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'date1'); ?> <br/>
                <?php
                $this->widget(
            'zii.widgets.jui.CJuiDatePicker',
            [
                        'name' => 'App_models_Lead[date1]',
                        'value' => $model['date1'],
                        'language' => 'ru',
                        'options' => ['dateFormat' => 'dd-mm-yy',
                        ],
                        'htmlOptions' => [
                            'style' => 'text-align:right; width:100%;',
                            'class' => 'form-control input-sm',
                        ],
                    ]
        );
                ?>
                <?php echo $form->error($model, 'date1'); ?>
            </div>
        </div>

        <div class="col-sm-1 col-xs-2">

            <div class="form-group">
                <?php echo $form->labelEx($model, 'date2'); ?><br/>
                <?php
                $this->widget(
                    'zii.widgets.jui.CJuiDatePicker',
                    [
                        'name' => 'App_models_Lead[date2]',
                        'value' => $model['date2'],
                        'language' => 'ru',
                        'options' => ['dateFormat' => 'dd-mm-yy',
                        ],
                        'htmlOptions' => [
                            'style' => 'text-align:right;  width:100%;',
                            'class' => 'form-control input-sm',
                        ],
                    ]
                );
                ?>
                <?php echo $form->error($model, 'date2'); ?>
            </div>
        </div>

        <div class="col-sm-2 col-xs-2">
            <div class="">
                <?php echo $form->labelEx($model, 'Город'); ?><br/>
                <?php
                echo CHtml::textField('town', $townName, [
                    'id' => 'town-selector',
                    'class' => 'form-control input-sm', 'style' => 'width:100%;',
                ]);
                ?>
                <?php
                echo $form->hiddenField($model, 'townId', ['id' => 'selected-town']);
                ?>
            </div>
        </div>

        <div class="col-sm-2 col-xs-2 hidden-xs">
            <div class="">
                <?php echo $form->labelEx($model, 'regionId'); ?><br/>
                <?php
                echo $form->dropDownList($model, 'regionId', [0 => 'Все'] + Region::getAllRegions(), [
                    'class' => 'form-control input-sm', 'style' => 'width:100%;',
                ]);
                ?>
            </div>
        </div>

        <div class="col-sm-1 col-xs-2">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'Источник'); ?><br/>
                <?php
                echo $form->dropDownList($model, 'sourceId', ['' => 'Все'] + Leadsource::getSourcesArray(true), [
                    'class' => 'form-control input-sm', 'style' => 'width:100%;',
                ]);
                ?>
            </div>
        </div>

        <div class="col-sm-1 col-xs-2 hidden-xs">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'type'); ?><br/>
                <?php
                echo $form->dropDownList($model, 'type', ['' => 'Все'] + Lead::getLeadTypesArray(), [
                    'class' => 'form-control input-sm', 'style' => 'width:100%;',
                ]);
                ?>
            </div>
        </div>

        <div class="col-sm-1 col-xs-2">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'leadStatus'); ?><br/>
                <?php
                echo $form->dropDownList($model, 'leadStatus', ['' => 'Все'] + Lead::getLeadStatusesArray(), [
                    'class' => 'form-control input-sm', 'style' => 'width:100%;',
                ]);
                ?>
            </div>
        </div>

        <div class="col-sm-1 col-xs-2">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'Телефон'); ?><br/>
                <?php echo $form->textField($model, 'phone', ['class' => 'form-control input-sm', 'style' => 'width:100%;']); ?>
                <?php echo $form->error($model, 'phone'); ?>
            </div>
        </div>


        <div class="col-sm-2 col-xs-12">
            <div class="buttons left-align"><br/>
                <?php echo CHtml::submitButton('Найти', ['class' => 'btn btn-block btn-primary input-sm']); ?>
            </div>
        </div>
    </div>
<?php $this->endWidget(); ?>
