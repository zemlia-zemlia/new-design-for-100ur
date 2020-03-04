<h1>Восстановление пароля</h1>

<?php if (empty($message)): ?>
    
<div class="login-form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'restorepassword-form',

    'enableAjaxValidation' => false,
        'htmlOptions' => [
                'class' => 'login-form',
                ],
]); ?>

    <div class="form-group">
    <?php echo $form->labelEx($model, 'email'); ?>

    <?php echo $form->textField($model, 'email', ['class' => 'form-control']); ?>

    <?php echo $form->error($model, 'email'); ?>
    </div>

    <div class="form-group">
    <?if (extension_loaded('gd')):?>
        <?php echo CHtml::activeLabelEx($model, 'verifyCode'); ?>
        <?php $this->widget('CCaptcha', ['clickableImage' => true, 'buttonLabel' => 'Показать другой код']); ?><br />
        <?php echo $form->textField($model, 'verifyCode', ['class' => 'form-control']); ?>
    <?endif; ?>
    </div>
    <?php echo CHtml::submitButton('Выслать пароль', ['class' => 'btn btn-primary']); ?>


<?php $this->endWidget(); ?>
</div><!-- form -->
<?php else: ?>
                
<?php
    if (isset($message)) {
        echo $message;
    }
?>                
                
<?php endif; ?>

