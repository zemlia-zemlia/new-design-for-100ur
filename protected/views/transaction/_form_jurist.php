<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'transaction-form',
    'enableAjaxValidation'=>false,
)); ?>

    <div class="row">
        <div class="col-sm-9">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'description', ['label' => 'На какой номер телефона вывести']); ?>
                <?php echo $form->textField($model, 'description',['class'=>'form-control phone-mask', 'placeholder' => 'Номер телефона']); ?>
                <?php echo $form->error($model, 'description'); ?>
            </div>  
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'sum', ['label' => 'сумма вывода']); ?>
            <?php echo $form->textField($model, 'sum', ['class'=>'form-control right-align', 'placeholder' => 'Сумма', 'value' => $model->sum / 100]); ?>
            <?php echo $form->error($model, 'sum'); ?>
            </div>       
        </div>
    </div>
    
    <?php echo CHtml::submitButton('Вывести средства', array('class'=>'btn btn-primary btn-block')); ?>
    
<?php $this->endWidget(); ?>

</div><!-- form -->