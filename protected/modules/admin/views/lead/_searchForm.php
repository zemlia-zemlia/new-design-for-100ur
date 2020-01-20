    <div class="row">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'lead-search-form',
            'method' => 'GET',
            'action' => Yii::app()->createUrl('admin/lead/index'),
            'htmlOptions' => array('class' => 'form-inline'),
            'enableAjaxValidation' => false,
        ));
        ?>

        <?php
        $townName = ($model->townId) ? Town::getName($model->townId) : '';
        //CustomFuncs::printr($model->attributes);
        ?>
        <div class="col-sm-1 col-xs-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'date1'); ?> <br/>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => "Lead[date1]",
                        'value' => $model['date1'],
                        'language' => 'ru',
                        'options' => array('dateFormat' => 'dd-mm-yy',
                        ),
                        'htmlOptions' => array(
                            'style' => 'text-align:right; width:100%;',
                            'class' => 'form-control input-sm'
                        )
                    )
                );
                ?>
                <?php echo $form->error($model, 'date1'); ?>
            </div>
        </div>

        <div class="col-sm-1 col-xs-6">

            <div class="form-group">
                <?php echo $form->labelEx($model, 'date2'); ?><br/>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name' => "Lead[date2]",
                        'value' => $model['date2'],
                        'language' => 'ru',
                        'options' => array('dateFormat' => 'dd-mm-yy',
                        ),
                        'htmlOptions' => array(
                            'style' => 'text-align:right;  width:100%;',
                            'class' => 'form-control input-sm'
                        )
                    )
                );
                ?>
                <?php echo $form->error($model, 'date2'); ?>
            </div>
        </div>

        <div class="col-sm-2 col-xs-6">
            <div class="">
                <?php echo $form->labelEx($model, 'town'); ?><br/>
                <?php
                echo CHtml::textField('town', $townName, array(
                    'id' => 'town-selector',
                    'class' => 'form-control input-sm', 'style' => 'width:100%;'
                ));
                ?>
                <?php
                echo $form->hiddenField($model, 'townId', array('id' => 'selected-town'));
                ?>
            </div>
        </div>

        <div class="col-sm-2 col-xs-6">
            <div class="">
                <?php echo $form->labelEx($model, 'regionId'); ?><br/>
                <?php
                echo $form->dropDownList($model, 'regionId', array(0 => 'Все') + Region::getAllRegions(), array(
                    'class' => 'form-control input-sm', 'style' => 'width:100%;'
                ));
                ?>
            </div>
        </div>

        <div class="col-sm-1 col-xs-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sourceId'); ?><br/>
                <?php
                echo $form->dropDownList($model, 'sourceId', array('' => 'Все') + Leadsource::getSourcesArray(true), array(
                    'class' => 'form-control input-sm', 'style' => 'width:100%;'
                ));
                ?>
            </div>
        </div>

        <div class="col-sm-1 col-xs-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'type'); ?><br/>
                <?php
                echo $form->dropDownList($model, 'type', array('' => 'Все') + Lead::getLeadTypesArray(), array(
                    'class' => 'form-control input-sm', 'style' => 'width:100%;'
                ));
                ?>
            </div>
        </div>

        <div class="col-sm-1 col-xs-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'leadStatus'); ?><br/>
                <?php
                echo $form->dropDownList($model, 'leadStatus', array('' => 'Все') + Lead::getLeadStatusesArray(), array(
                    'class' => 'form-control input-sm', 'style' => 'width:100%;'
                ));
                ?>
            </div>
        </div>

        <div class="col-sm-1 col-xs-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'phone'); ?><br/>
                <?php echo $form->textField($model, 'phone', array('class' => 'form-control input-sm', 'style' => 'width:100%;')); ?>
                <?php echo $form->error($model, 'phone'); ?>
            </div>
        </div>


        <div class="col-sm-2 col-xs-12">
            <div class="buttons left-align"><br/>
                <?php echo CHtml::submitButton("Найти", array('class' => 'btn btn-block btn-primary input-sm')); ?>
            </div>
        </div>
    </div>
<?php $this->endWidget(); ?>
