<?php
/* @var $this QuestionCategoryController */
/* @var $model QuestionCategory */
/* @var $form CActiveForm */
?>


<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'question-category-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => [
        'enctype' => 'multipart/form-data',
    ],
)); ?>

<div class="row">

    <div class="col-md-8">
        <?php echo $form->errorSummary($model, "Исправьте ошибки"); ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'description1'); ?>
            <?php
            $this->widget('application.extensions.cleditor.ECLEditor', array(
                'model' => $model,
                'attribute' => 'description1', //Model attribute name.
                'options' => array(
                    'width' => 'auto',
                    'height' => 800,
                    'useCSS' => true,
                ),
                'value' => $model->description1,
                'htmlOptions' => array(
                    'class' => 'form-control',
                ),
            ));

            ?>
            <?php echo $form->error($model, 'description1'); ?>
        </div>
		<hr/>
		<h2>Теги для редактирования текста статей:</h2>
		<h4>blockquote</h4>
		<h4> abbr title="Дорожно-патрульная служба" ДПС abbr </h4>


    </div>

    <div class="col-md-4">

        <div class="vert-margin30">
            <?php echo $form->labelEx($model, 'imageFile'); ?>

            <?php if($model->image):?>
                <?php echo CHtml::image($model->getImagePath(), '', ['class' => 'img-responsive']);?>
            <?php endif;?>

            <?php echo $form->fileField($model, 'imageFile', array('class' => 'form-control')); ?>
            <div class="length-counter"></div>
            <?php echo $form->error($model, 'imageFile'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model,'publish_date'); ?>
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker',
                array(
                    'name'=>"QuestionCategory[publish_date]",
                    'value'=>$model['publish_date'],
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
            <?php echo $form->error($model,'publish_date'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'seoTitle'); ?>
            <?php echo $form->textField($model, 'seoTitle', array('class' => 'form-control strlen-count')); ?>
            <div class="length-counter"></div>
            <?php echo $form->error($model, 'seoTitle'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'seoDescription'); ?>
            <?php echo $form->textArea($model, 'seoDescription', array('class' => 'form-control strlen-count', 'rows' => 6)); ?>
            <div class="length-counter"></div>
            <?php echo $form->error($model, 'seoDescription'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'seoH1'); ?>
            <?php echo $form->textField($model, 'seoH1', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'seoH1'); ?>
        </div>

        <hr/>
        <div class="form-group">
            <?php echo $form->labelEx($model, 'name'); ?>
            <?php echo $form->textField($model, 'name', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'name'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'seoKeywords'); ?>
            <?php echo $form->textArea($model, 'seoKeywords', array('class' => 'form-control', 'rows' => 4)); ?>
            <?php echo $form->error($model, 'seoKeywords'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'parentId'); ?>
            <?php echo $form->dropDownList($model, 'parentId', QuestionCategory::getCategoriesIdsNames(), array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'parentId'); ?>
        </div>

        <div class="form-group checkbox">
            <label>
                <?php echo $form->checkBox($model, 'isDirection'); ?>
                <?php echo $model->getAttributeLabel('isDirection'); ?>
            </label>
            <?php echo $form->error($model, 'parentId'); ?>
        </div>

        <div class="buttons">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class' => 'btn btn-block btn-primary')); ?>
        </div>
    </div>

</div>


<?php $this->endWidget(); ?>
