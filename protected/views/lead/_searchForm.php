<div class="vert-margin30 inside text-right">
    <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'lead-search-form',
            'method' => 'GET',
            'action' => Yii::app()->createUrl('lead/index'),
            'htmlOptions' => array('class' => 'form-inline'),
            'enableAjaxValidation' => false,
        ));
    ?>
    
    <div class="form-group">
        <?php
        echo $form->dropDownList($model, 'regionId', array(0 => 'Все регионы') + Region::getAllRegions(), array(
            'class' => 'form-control',
        ));
        ?>
    </div>


    <div class="form-group buttons left-align">
        <?php echo CHtml::submitButton("Найти заявки", array('class' => 'btn btn-primary')); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>