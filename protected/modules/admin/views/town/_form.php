<?php
/* @var $this TownController */
/* @var $model Town */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'town-form',
	'enableAjaxValidation'=>false,
        'htmlOptions'   =>  array(
            'enctype'   =>  'multipart/form-data',
            ),
)); ?>

	<?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>
<?php if($model->isNewRecord):?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textArea($model,'name',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'ocrug'); ?>
		<?php echo $form->textArea($model,'ocrug',array('rows'=>6, 'cols'=>50, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'ocrug'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'country'); ?>
		<?php echo $form->textField($model,'country',array('size'=>32,'maxlength'=>32, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'country'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'alias'); ?>
		<?php echo $form->textField($model,'alias',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'alias'); ?>
	</div>
<?php endif;?>

        <div class="form-group">
            <label>
		<?php echo $form->checkBox($model,'isCapital'); ?>
                <?php echo $model->getAttributeLabel('isCapital');?>
            </label>
		<?php echo $form->error($model,'isCapital'); ?>
	</div>
 <!--        
	<div class="form-group">
		<?php echo $form->labelEx($model,'size'); ?>
		<?php echo $form->textField($model,'size',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'size'); ?>
	</div>
-->	
	<div class="form-group">
		<?php echo $form->labelEx($model,'seoTitle'); ?>
		<?php echo $form->textField($model,'seoTitle',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'seoTitle'); ?>
	</div>
	
	<div class="form-group">
		<?php echo $form->labelEx($model,'seoDescription'); ?>
		<?php echo $form->textArea($model,'seoDescription',array('rows'=>3,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'seoDescription'); ?>
	</div>
	
	<div class="form-group">
		<?php echo $form->labelEx($model,'seoKeywords'); ?>
		<?php echo $form->textArea($model,'seoKeywords',array('rows'=>3,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'seoKeywords'); ?>
	</div>
	
	<div class="form-group">
		<?php echo $form->labelEx($model,'photoFile'); ?>
		<?php echo $form->fileField($model, 'photoFile');?>
		<?php echo $form->error($model,'photoFile'); ?>
	</div> 
	
		
	<div class="form-group">
		<?php echo $form->labelEx($model,'description1'); ?>
                <?php   
                    $this->widget('application.extensions.cleditor.ECLEditor', array(
                            'model'     =>  $model,
                            'attribute' =>  'description1', //Model attribute name.
                            'options'   =>  array(
                                'width'     =>  'auto',
                                'height'    =>  300,
                                'useCSS'    =>  true,
                            ),
                            'value'     =>  $model->description1,
                            'htmlOptions'   =>  array(
                                'class'=>'form-control',
                            ),
                        ));

                ?>
		<?php //echo $form->textArea($model,'description1',array('rows'=>6, 'class'=>'form-control')); ?>
		<?php echo $form->error($model,'description1'); ?>
	</div>
<!--
	<div class="form-group">
		<?php echo $form->labelEx($model,'description2'); ?>
                <?php   
                    $this->widget('application.extensions.cleditor.ECLEditor', array(
                            'model'     =>  $model,
                            'attribute' =>  'description2', //Model attribute name.
                            'options'   =>  array(
                                'width'     =>  'auto',
                                'height'    =>  300,
                                'useCSS'    =>  true,
                            ),
                            'value'     =>  $model->description2,
                            'htmlOptions'   =>  array(
                                'class'=>'form-control',
                            ),
                        ));

                ?>
		<?php echo $form->error($model,'description2'); ?>
	</div>
-->        

        
	<div class="form-group">
		<?php echo CHtml::submitButton('Сохранить', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
