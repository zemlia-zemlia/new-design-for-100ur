<?php
    $model->sum = MoneyFormat::rubles($model->sum);
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'transaction-form',
        'enableAjaxValidation' => false,
    )); ?>
    <div class="row">
        <div class="col-sm-9">
            <div class="form-group">
                <?php echo $form->textField($model, 'comment', array('class' => 'form-control right-align', 'placeholder' => 'Номер кошелька Яндекс Деньги')); ?>
                <?php echo $form->error($model, 'comment'); ?>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <?php echo $form->textField($model, 'sum', array('class' => 'form-control right-align', 'placeholder' => 'Сумма')); ?>
                <?php echo $form->error($model, 'sum'); ?>
            </div>
        </div>
    </div>

    <?php echo CHtml::submitButton('Вывести средства', array('class' => 'btn btn-primary btn-block')); ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->