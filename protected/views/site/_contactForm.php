<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'contact-form',
        'action' => Yii::app()->createUrl('site/contacts/'),
    'enableAjaxValidation' => false,
        'htmlOptions' => [
            'class' => '',
        ],
]); ?>

<div class="container-fluid">
    <div class="row">

        <div class="col-sm-12">  
            <div class="form-group">
                <?php echo $form->labelEx($model, 'name'); ?>
                <?php echo $form->textField($model, 'name', ['class' => 'form-control', 'placeholder' => 'Иван Иванов']); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
            
            <div class="form-group">
                <?php echo $form->labelEx($model, 'email'); ?>
                <?php echo $form->textField($model, 'email', ['class' => 'form-control', 'placeholder' => $model->getAttributeLabel('email')]); ?>
                <?php echo $form->error($model, 'email'); ?>
            </div>
            
            <div class="form-group">
                <?php echo $form->labelEx($model, 'message'); ?>
                <?php echo $form->textArea($model, 'message', ['class' => 'form-control', 'rows' => '6', 'placeholder' => $model->getAttributeLabel('message')]); ?>
                <?php echo $form->error($model, 'message'); ?>
            </div>
            
            <?if (extension_loaded('gd')):?>
                <div class="form-group">
                    <?php echo CHtml::activeLabelEx($model, 'verifyCode'); ?>
                    <br />
                    <?$this->widget('CCaptcha', ['clickableImage' => true, 'buttonLabel' => 'Показать другой код']); ?><br />
                    <?php echo $form->textField($model, 'verifyCode', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'verifyCode'); ?>
                </div>
            <?endif; ?>

            <?php echo CHtml::submitButton('Отправить', ['class' => 'btn btn-success btn-block']); ?>
        </div>

    </div>
</div>

    


<?php $this->endWidget(); ?>
