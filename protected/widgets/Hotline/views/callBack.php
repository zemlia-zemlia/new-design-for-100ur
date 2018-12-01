<div class="hotline-wrapper">
    <div>
        <strong>Горячая линия</strong> - юрист позвонит Вам
    </div>

    <?php $form=$this->beginWidget('CActiveForm', array(
	'id'            =>  'lead-search-form',
        'method'        =>  'POST',
        'action'        =>  Yii::app()->createUrl('question/callback'),
        'htmlOptions'   =>  array('class'=>'form-inline'),
	'enableAjaxValidation'=>false,
    )); ?>
    
    <div class="form-group">
        <?php echo $form->labelEx($model,'town'); ?>
        <?php echo CHtml::textField('town', $townName, array(
                        'id'            =>  'town-selector', 
                        'class'         =>  'form-control input-sm',
        )); ?>
        <?php
            echo $form->hiddenField($model, 'townId', array('id'=>'selected-town'));
        ?>
    </div>
    
    <div class="form-group buttons left-align">
        <?php echo CHtml::submitButton("Заказать звонок", array('class'=>'btn btn-default input-sm')); ?>
    </div>

<?php $this->endWidget(); ?>
    
</div>