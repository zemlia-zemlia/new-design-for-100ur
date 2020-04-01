<?php
/* @var $this TownController */

use App\models\Town;

/* @var $model Town */
/* @var $form CActiveForm */
?>


<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'town-form',
    'enableAjaxValidation' => false,
        'htmlOptions' => [
            'enctype' => 'multipart/form-data',
            ],
]); ?>

	<?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>
<?php if ($model->isNewRecord):?>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'name'); ?>
		<?php echo $form->textArea($model, 'name', ['rows' => 6, 'cols' => 50, 'class' => 'form-control']); ?>
		<?php echo $form->error($model, 'name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'alias'); ?>
		<?php echo $form->textField($model, 'alias', ['class' => 'form-control']); ?>
		<?php echo $form->error($model, 'alias'); ?>
	</div>
<?php endif; ?>

        <div class="form-group">
            <label>
		<?php echo $form->checkBox($model, 'isCapital'); ?>
                <?php echo $model->getAttributeLabel('isCapital'); ?>
            </label>
		<?php echo $form->error($model, 'isCapital'); ?>
	</div>
 <!--        
	<div class="form-group">
		<?php echo $form->labelEx($model, 'size'); ?>
		<?php echo $form->textField($model, 'size', ['class' => 'form-control']); ?>
		<?php echo $form->error($model, 'size'); ?>
	</div>
-->	
	<div class="form-group">
		<?php echo $form->labelEx($model, 'seoTitle'); ?>
		<?php echo $form->textField($model, 'seoTitle', ['class' => 'form-control']); ?>
		<?php echo $form->error($model, 'seoTitle'); ?>
	</div>
	
	<div class="form-group">
		<?php echo $form->labelEx($model, 'seoDescription'); ?>
		<?php echo $form->textArea($model, 'seoDescription', ['rows' => 3, 'class' => 'form-control']); ?>
		<?php echo $form->error($model, 'seoDescription'); ?>
	</div>
	
	<div class="form-group">
		<?php echo $form->labelEx($model, 'seoKeywords'); ?>
		<?php echo $form->textArea($model, 'seoKeywords', ['rows' => 3, 'class' => 'form-control']); ?>
		<?php echo $form->error($model, 'seoKeywords'); ?>
	</div>
	
	<div class="form-group">
		<?php echo $form->labelEx($model, 'photoFile'); ?>
		<?php echo $form->fileField($model, 'photoFile'); ?>
		<?php echo $form->error($model, 'photoFile'); ?>
	</div> 
	
		
	<div class="form-group">
		<?php echo $form->labelEx($model, 'description1'); ?>
                <?php
                    $this->widget('application.extensions.cleditor.ECLEditor', [
                            'model' => $model,
                            'attribute' => 'description1', //Model attribute name.
                            'options' => [
                                'width' => 'auto',
                                'height' => 300,
                                'useCSS' => true,
                            ],
                            'value' => $model->description1,
                            'htmlOptions' => [
                                'class' => 'form-control',
                            ],
                        ]);

                ?>
		<?php //echo $form->textArea($model,'description1',array('rows'=>6, 'class'=>'form-control'));?>
		<?php echo $form->error($model, 'description1'); ?>
	</div>
<!--
	<div class="form-group">
		<?php echo $form->labelEx($model, 'description2'); ?>
                <?php
                    $this->widget('application.extensions.cleditor.ECLEditor', [
                            'model' => $model,
                            'attribute' => 'description2', //Model attribute name.
                            'options' => [
                                'width' => 'auto',
                                'height' => 300,
                                'useCSS' => true,
                            ],
                            'value' => $model->description2,
                            'htmlOptions' => [
                                'class' => 'form-control',
                            ],
                        ]);

                ?>
		<?php echo $form->error($model, 'description2'); ?>
	</div>
-->        

        
	<div class="form-group">
		<?php echo CHtml::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
	</div>

<?php $this->endWidget(); ?>
