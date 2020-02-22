<?php
/* @var $this MoneyController */
/* @var $model Money */
/* @var $form CActiveForm */
?>


<?php $form = $this->beginWidget('CActiveForm', [
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
    'htmlOptions' => ['class' => 'form-inline'],
    'enableAjaxValidation' => false,
]); ?>
    <div class="form-group">
        <?php echo $form->dropDownList($model, 'accountId', ['' => 'Все счета'] + Money::getAccountsArray(), ['class' => 'form-control']); ?>
    </div>

    <div class="form-group">
        <?php echo $form->dropDownList($model, 'direction', ['' => 'Все статьи'] + Money::getDirectionsArray(), ['class' => 'form-control']); ?>
    </div>

    <div class="form-group buttons left-align">
        <?php echo CHtml::submitButton('Найти', ['class' => 'btn btn-block btn-primary']); ?>
    </div>


<?php $this->endWidget(); ?>