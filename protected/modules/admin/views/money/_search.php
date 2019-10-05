<?php
/* @var $this MoneyController */
/* @var $model Money */
/* @var $form CActiveForm */
?>


<?php $form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
    'htmlOptions' => array('class' => 'form-inline'),
    'enableAjaxValidation' => false,
)); ?>
    <div class="form-group">
        <?php echo $form->dropDownList($model, 'accountId', array('' => 'Все счета') + Money::getAccountsArray(), array('class' => 'form-control')); ?>
    </div>

    <div class="form-group">
        <?php echo $form->dropDownList($model, 'direction', array('' => 'Все статьи') + Money::getDirectionsArray(), array('class' => 'form-control')); ?>
    </div>

    <div class="form-group buttons left-align">
        <?php echo CHtml::submitButton('Найти', array('class' => 'btn btn-block btn-primary')); ?>
    </div>


<?php $this->endWidget(); ?>