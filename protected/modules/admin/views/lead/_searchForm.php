<div class="vert-margin30 flat-panel  inside">
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

    <div class="form-group">
        <?php echo $form->labelEx($model, 'date1'); ?> <br/>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => "Lead100[date1]",
            'value' => $model['date1'],
            'language' => 'ru',
            'options' => array('dateFormat' => 'dd-mm-yy',
            ),
            'htmlOptions' => array(
                'style' => 'text-align:right; width:85px;',
                'class' => 'form-control input-sm'
            )
                )
        );
        ?>
<?php echo $form->error($model, 'date1'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'date2'); ?><br/>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => "Lead100[date2]",
            'value' => $model['date2'],
            'language' => 'ru',
            'options' => array('dateFormat' => 'dd-mm-yy',
            ),
            'htmlOptions' => array(
                'style' => 'text-align:right;  width:85px;',
                'class' => 'form-control input-sm'
            )
                )
        );
        ?>
<?php echo $form->error($model, 'date2'); ?>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'town'); ?><br/>
        <?php
        echo CHtml::textField('town', $townName, array(
            'id' => 'town-selector',
            'class' => 'form-control',
        ));
        ?>
<?php
echo $form->hiddenField($model, 'townId', array('id' => 'selected-town'));
?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'regionId'); ?><br/>
<?php
echo $form->dropDownList($model, 'regionId', array(0 => 'Все') + Region::getAllRegions(), array(
    'class' => 'form-control',
));
?>
    </div>

    <div class="form-group">
<?php echo $form->labelEx($model, 'sourceId'); ?><br/>
        <?php
        echo $form->dropDownList($model, 'sourceId', array('' => 'Все') + Leadsource100::getSourcesArray(true), array(
            'class' => 'form-control',
        ));
        ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'type'); ?><br/>
        <?php
        echo $form->dropDownList($model, 'type', array('' => 'Все') + Lead100::getLeadTypesArray(), array(
            'class' => 'form-control',
        ));
        ?>
    </div>

    <div class="form-group">
    <?php echo $form->labelEx($model, 'phone'); ?><br/>
<?php echo $form->textField($model, 'phone', array('class' => 'form-control input-sm', 'style' => 'width:100px;')); ?>
<?php echo $form->error($model, 'phone'); ?>
    </div>

    <div class="form-group buttons left-align"><br/>
<?php echo CHtml::submitButton("Найти", array('class' => 'btn btn-primary input-sm')); ?>
    </div>

<?php $this->endWidget(); ?>
</div>