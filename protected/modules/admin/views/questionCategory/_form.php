<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */
/* @var $form CActiveForm */
?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'question-category-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля</p>

	<?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>

	<div class="form-group">
            <?php echo $form->labelEx($model,'name'); ?>
            <?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'name'); ?>
	</div>
	
    <div class="form-group">
            <?php echo $form->labelEx($model,'seoTitle'); ?>
            <?php echo $form->textField($model,'seoTitle',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'seoTitle'); ?>
	</div>
        
    <div class="form-group">
            <?php echo $form->labelEx($model,'seoDescription'); ?>
            <?php echo $form->textArea($model,'seoDescription',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'seoDescription'); ?>
	</div> 
	
    <div class="form-group">
            <?php echo $form->labelEx($model,'seoH1'); ?>
            <?php echo $form->textField($model,'seoH1',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'seoH1'); ?>
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
            <?php echo $form->error($model,'description1'); ?>
	</div>
    <? /*    
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
	</div> */ ?>
        

        
        <div class="form-group">
            <?php echo $form->labelEx($model,'seoKeywords'); ?>
            <?php echo $form->textArea($model,'seoKeywords',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'seoKeywords'); ?>
	</div>

	<div class="form-group">
            <?php echo $form->labelEx($model,'parentId'); ?>
            <?php echo $form->dropDownList($model,'parentId', QuestionCategory::getCategoriesIdsNames(), array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'parentId'); ?>
	</div>

        <div class="form-group checkbox">
            <label>
            <?php echo $form->checkBox($model,'isDirection'); ?>
            <?php echo $model->getAttributeLabel('isDirection');?>
            </label>
            <?php echo $form->error($model,'parentId'); ?>
	</div>
        
	<div class="row buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>
