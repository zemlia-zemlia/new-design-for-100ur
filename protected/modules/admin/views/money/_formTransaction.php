<?php
/* @var $this MoneyController */
/* @var $model MoneyMove */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'internal-transaction-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => ['autocomplete' => "off"]
    )); ?>

    <?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'fromAccount'); ?>
                <?php echo $form->dropDownList($model, 'fromAccount', Money::getAccountsArray(), array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'fromAccount'); ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'toAccount'); ?>
                <?php echo $form->dropDownList($model, 'toAccount', Money::getAccountsArray(), array('class' => 'form-control')); ?>
                <?php echo $form->error($model, 'toAccount'); ?>
            </div>
        </div>

    </div>


    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'datetime'); ?>
                <?php $this->widget(
                    'zii.widgets.jui.CJuiDatePicker',
                    array(
                        'name' => "MoneyMove[datetime]",
                        'value' => $model['datetime'],
                        'language' => 'ru',
                        'options' => array('dateFormat' => 'dd-mm-yy',
                        ),
                        'htmlOptions' => array(
                            'style' => 'text-align:right;',
                            'class' => 'form-control'
                        )
                    )
                );
                ?>
                <?php echo $form->error($model, 'datetime'); ?>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sum'); ?>
                <?php echo $form->textField($model, 'sum', array('class' => 'form-control right-align')); ?>
                <?php echo $form->error($model, 'sum'); ?>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'comment'); ?>
                <?php echo $form->textArea($model, 'comment', array('rows' => '3', 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'comment'); ?>
            </div>
        </div>
    </div>

    <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-primary')); ?>

    <?php $this->endWidget(); ?>

</div><!-- form -->