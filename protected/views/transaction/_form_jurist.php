<div class="form">

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'transaction-form',
        'enableAjaxValidation' => false,
    )); ?>
    <h3>Вывести</h3>
    <div class="row">
        <div class="col-sm-7">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'description', ['label' => 'Номер телефона']); ?>
                <?php echo $form->textField($model, 'description', ['class' => 'form-control phone-mask', 'placeholder' => 'Номер телефона']); ?>
                <?php echo $form->error($model, 'description'); ?>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sum', ['label' => 'сумма вывода']); ?>
                <?php echo $form->textField($model, 'sum', ['class' => 'form-control right-align', 'placeholder' => 'Сумма', 'value' => $model->sum / 100]); ?>
                <?php echo $form->error($model, 'sum'); ?>
            </div>
        </div>
    </div>
    <div class="center-align">
        <p class="small"><strong>Внимание!</strong> Вывод средств возможен только на баланс любого мобильного телефона.</p>
        <?php echo CHtml::submitButton('Вывести средства', array('class' => 'btn btn-default')); ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->