<?php
/* @var $this LeadsourceController */
/* @var $model Leadsource */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'leadsource-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>        
        
        
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'type'); ?>
                    <?php echo $form->dropDownList($model,'type', Leadsource100::getTypes(), array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'type'); ?>
                </div>
            </div>
            <div class="col-sm-8">
                    <p>
                        Как вы хотите зарабатывать:
                    </p>
                    <ul>
                        <li>Лиды: оплата в зависимости от региона клиента (от 20 до 200 руб.)</li>
                        <li>Вопросы: фиксированная оплата за опубликованный вопрос независимо от региона (<?php echo Yii::app()->params['questionPrice'];?> руб.)</li>
                    </ul>
            </div>
        </div>
        
        
        <div class="row">
            <div class="col-sm-6">
        
                <div class="form-group">
                    <?php echo $form->labelEx($model,'name'); ?>
                    <?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'name'); ?>
                </div>
            </div>
        </div>
        

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <?php echo $form->labelEx($model,'description'); ?>
                    <?php echo $form->textArea($model,'description',array('class'=>'form-control','rows'=>'3')); ?>
                    <?php echo $form->error($model,'description'); ?>
                </div>
            </div>
        </div>

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->