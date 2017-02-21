<?php
/* @var $this MoneyController */
/* @var $model Money */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'money-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
		<?php echo $form->labelEx($model,'datetime'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',
                array(
                'name'=>"Money[datetime]",
                'value'=>$model['datetime'],
                'language'=>'ru',
                'options' => array('dateFormat'=>'dd-mm-yy',
                                 ),
                'htmlOptions' => array(
                    'style'=>'text-align:right;',
                    'class'=>'form-control'
                    )    
                )
               );
            ?>
                   <?php echo $form->error($model,'datetime'); ?>
           </div>
        </div>
        
        <div class="col-sm-6">
            <div class="form-group">
		<?php echo $form->labelEx($model,'accountId'); ?>
		<?php echo $form->dropDownList($model,'accountId', Money::getAccountsArray(), array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'accountId'); ?>
            </div>
        </div>

    </div>
	

	
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
		<?php echo $form->labelEx($model,'type'); ?><br >
		<?php echo $form->radioButtonList($model,'type', array(Money::TYPE_INCOME=>'Доход', Money::TYPE_EXPENCE=>'Расход')); ?>
		<?php echo $form->error($model,'type'); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('class'=>'form-control right-align')); ?>
		<?php echo $form->error($model,'value'); ?>
            </div>
        </div>
    </div>

	
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
		<?php echo $form->labelEx($model,'direction'); ?>
		<?php echo $form->dropDownList($model,'direction', Money::getDirectionsArray(), array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'direction'); ?>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textArea($model,'comment',array('rows'=>'3','class'=>'form-control')); ?>
		<?php echo $form->error($model,'comment'); ?>
            </div>
        </div>
    </div>

    <?php echo CHtml::submitButton('Сохранить', array('class'=>'btn btn-primary')); ?>
    
    <?php if(!$model->isNewRecord):?>
        <?php echo CHtml::link('Удалить запись', Yii::app()->createUrl('admin/money/delete',array('id'=>$model->id)), array('class'=>'btn btn-danger', 'onclick'=>'return confirm("Удалить запись?")'));?>
    <?php endif;?>
<?php $this->endWidget(); ?>

</div><!-- form -->