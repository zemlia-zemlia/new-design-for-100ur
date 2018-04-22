<div class="new-lead-form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'mail-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>
    
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'recipientEmail'); ?>
                <?php echo $form->textField($model, 'recipientEmail', ['class' => 'form-control']); ?>
                <?php echo $form->error($model, 'recipientEmail'); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <?php echo $form->labelEx($model, 'roleId'); ?>
                <?php echo $form->dropDownList($model, 'roleId', ['' => 'Не выбрано'] + User::getRoleNamesArray(), ['class' => 'form-control']); ?>
                <?php echo $form->error($model, 'roleId'); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'subject'); ?>
        <?php echo $form->textField($model, 'subject', ['class' => 'form-control', 'placeholder' => 'Заголовок рассылки']); ?>
        <?php echo $form->error($model, 'subject'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'message'); ?>
        <?php echo $form->textArea($model, 'message', ['class' => 'form-control', 'rows' => 15]); ?>
        <?php echo $form->error($model, 'message'); ?>
    </div>
    
    <?php echo CHtml::submitButton('Отправить рассылку',array('class'=>'btn btn-success btn-lg')); ?>
    
    <?php $this->endWidget(); ?>

</div>