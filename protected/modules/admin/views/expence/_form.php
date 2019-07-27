<?php
    $model->expences = MoneyFormat::rubles($model->expences);
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'expence-form',
	'enableAjaxValidation'=>false,
)); ?>

    <?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',
                array(
                'name'=>"Expence[date]",
                'value'=>$model['date'],
                'language'=>'ru',
                'options' => array('dateFormat'=>'yy-mm-dd',
                                 ),
                'htmlOptions' => array(
                    'style'=>'text-align:right;',
                    'class'=>'form-control'
                    )    
                )
               );
            ?>
                   <?php echo $form->error($model,'date'); ?>
           </div>
        </div>
        
        <div class="col-sm-6">
            <div class="form-group">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->dropDownList($model,'type', Expence::getTypes(), array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'type'); ?>
            </div>
        </div>

    </div>
	

	
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
		<?php echo $form->labelEx($model,'expences'); ?>
		<?php echo $form->textField($model,'expences',array('class'=>'form-control right-align')); ?>
		<?php echo $form->error($model,'expences'); ?>
            </div>
        </div>
        <div class="col-sm-6">
            
        </div>
    </div>
    
    <div class="form-group">
        <?php echo $form->labelEx($model,'comment'); ?>
        <?php echo $form->textField($model,'comment',array('class'=>'form-control')); ?>
        <?php echo $form->error($model,'comment'); ?>
    </div>

    <?php echo CHtml::submitButton('Сохранить', array('class'=>'btn btn-primary')); ?>
    
    <?php if(!$model->isNewRecord):?>
        <?php echo CHtml::link('Удалить запись', Yii::app()->createUrl('admin/expence/delete',array('id'=>$model->id)), array('class'=>'btn btn-danger', 'onclick'=>'return confirm("Удалить запись?")'));?>
    <?php endif;?>
<?php $this->endWidget(); ?>

</div><!-- form -->