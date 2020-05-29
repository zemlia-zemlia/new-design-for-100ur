<?php
/* @var $this MoneyController */

use App\models\Money;

/* @var $model Money */
/* @var $form CActiveForm */
?>


<?php $form = $this->beginWidget('CActiveForm', [
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
    'htmlOptions' => ['class' => 'form-inline'],
    'enableAjaxValidation' => false,
]); ?>

    <div class="row">
        <div class="col-md-2 col-sm-4 col-xs-4">
            <div class="form-group">
                <?php echo $form->dropDownList($model, 'accountId', ['' => 'Все счета'] + Money::getAccountsArray(),
                    [
                        'class' => 'form-control',
                        'style' => 'width:100%;'
                    ]); ?>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-4">
            <div class="form-group">
                <?php echo $form->dropDownList($model, 'direction', ['' => 'Все статьи'] + Money::getDirectionsArray(),
                    [
                        'class' => 'form-control',
                        'style' => 'width:100%;'
                    ]); ?>
            </div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-4">
            <div class="form-group buttons left-align">
                <?php echo CHtml::submitButton('Найти',
                    [
                        'class' => 'btn btn-block btn-primary',
                        'style' => 'width:100%;'
                    ]); ?>
            </div>
        </div>
    </div>

<?php $this->endWidget(); ?>