<?php

use App\models\Expence;

$model->expences = MoneyFormat::rubles($model->expences);
?>

<div class="form">

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'expence-form',
    'enableAjaxValidation' => false,
]); ?>

    <?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
		<?php echo $form->labelEx($model, 'date'); ?>
		<?php $this->widget(
    'zii.widgets.jui.CJuiDatePicker',
    [
                'name' => 'App\models\Expence[date]',
                'value' => $model['date'],
                'language' => 'ru',
                'options' => ['dateFormat' => 'yy-mm-dd',
                                 ],
                'htmlOptions' => [
                    'style' => 'text-align:right;',
                    'class' => 'form-control',
                    ],
                ]
);
            ?>
                   <?php echo $form->error($model, 'date'); ?>
           </div>
        </div>
        
        <div class="col-sm-6">
            <div class="form-group">
		<?php echo $form->labelEx($model, 'type'); ?>
		<?php echo $form->dropDownList($model, 'type', Expence::getTypes(), ['class' => 'form-control']); ?>
		<?php echo $form->error($model, 'type'); ?>
            </div>
        </div>

    </div>
	

	
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
		<?php echo $form->labelEx($model, 'expences'); ?>
		<?php echo $form->textField($model, 'expences', ['class' => 'form-control right-align']); ?>
		<?php echo $form->error($model, 'expences'); ?>
            </div>
        </div>
        <div class="col-sm-6">
            
        </div>
    </div>
    
    <div class="form-group">
        <?php echo $form->labelEx($model, 'comment'); ?>
        <?php echo $form->textField($model, 'comment', ['class' => 'form-control']); ?>
        <?php echo $form->error($model, 'comment'); ?>
    </div>

    <?php echo CHtml::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
    
    <?php if (!$model->isNewRecord):?>
        <?php echo CHtml::link('Удалить запись', Yii::app()->createUrl('admin/expence/delete', ['id' => $model->id]), ['class' => 'btn btn-danger', 'onclick' => 'return confirm("Удалить запись?")']); ?>
    <?php endif; ?>
<?php $this->endWidget(); ?>

</div><!-- form -->