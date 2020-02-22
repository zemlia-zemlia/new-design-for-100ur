
<div class="flat-panel inside">
<h1>Восстановление пароля</h1>

<?php if (empty($message)): ?>
    
<div class="row">
    <div class="col-md-6 col-md-offset-3">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'restorepassword-form',

    'enableAjaxValidation' => false,
]); ?>




<div class="form-group">
    <?php echo $form->labelEx($model, 'email'); ?>
    <?php echo $form->textField($model, 'email', ['class' => 'form-control']); ?>
    <?php echo $form->error($model, 'email'); ?>
</div>

<?if (extension_loaded('gd')):?>
    <div class="form-group">
        <?php echo CHtml::activeLabelEx($model, 'verifyCode'); ?>
        <br />
        <?$this->widget('CCaptcha', ['clickableImage' => true, 'buttonLabel' => 'Показать другой']); ?><br />
        <?php echo $form->textField($model, 'verifyCode', ['class' => 'form-control']); ?>
    </div>
<?endif; ?>

<div class="form-group">
    <?php echo CHtml::submitButton('Выслать пароль', ['class' => 'yellow-button center-block']); ?>
</div>

<?php $this->endWidget(); ?>
        </div>
</div><!-- row -->
<?php else: ?>
                
<?php
    if (isset($message)) {
        echo $message;
    }
?>                
                
<?php endif; ?>


</div>
