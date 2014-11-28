<?php
/* @var $this PostController */
/* @var $model Post */
/* @var $form CActiveForm */
?>

<div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-form',
	'enableAjaxValidation'=>false,
)); ?>
<div class="row">
    <div class="col-md-8">
        <p class="note"><span class="required">*</span> обязательные поля</p>

        <?php echo $form->errorSummary($model); ?>
        
<div class="form-group">
        <?php echo $form->labelEx($model,'title'); ?>
        <?php echo $form->textField($model,'title',array('class'=>'form-control','maxlength'=>256)); ?>
        <?php echo $form->error($model,'title'); ?>
</div>

       <div class="form-group"> 
        <?php echo $form->labelEx($model,'preview'); ?>
        <?php   
            $this->widget('application.extensions.cleditor.ECLEditor', array(
                    'model'     =>  $model,
                    'attribute' =>  'preview', //Model attribute name.
                    'options'   =>  array(
                        'width'     =>  '99%',
                        'height'    =>  200,
                        'useCSS'    =>  true,
                    ),
                    'value'     =>  $model->preview,
                    'htmlOptions'   =>  array(
                        'class'=>'form-control',
                    ),
                ));

        ?>
        <?php echo $form->error($model,'preview'); ?>
       </div>
           
        <div class="form-group">   
        <?php echo $form->labelEx($model,'text'); ?>
        <?php   
            $this->widget('application.extensions.cleditor.ECLEditor', array(
                    'model'     =>  $model,
                    'attribute' =>  'text', //Model attribute name.
                    'options'   =>  array(
                        'width'     =>  '99%',
                        'height'    =>  500,
                        'useCSS'    =>  true,
                    ),
                    'value'     =>  $model->text,
                    'htmlOptions'   =>  array(
                            'class'=>'form-control',
                        ),
                ));

        ?>
        <?php echo $form->error($model,'text'); ?>
    </div>

        
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать пост' : 'Сохранить пост', array('class'=>'btn btn-primary btn-large')); ?>
    <?php
        if(!$model->isNewRecord) {
            echo CHtml::link('Не сохранять', Yii::app()->createUrl('post/view', array('id'=>$model->id)), array('class'=>'btn btn-large'));
        }
    ?>
    
    </div>
    <div class="col-md-4">
        <h2>Категории</h2>
        <p>Каждый пост может относиться к нескольким категориям. Отметьте нужные категории, зажав клавишу Ctrl (Command в MacOS)</p>
        <div class="form-group"> 
            <?php echo $form->dropDownList($model,'categories', $categoriesArray,
                array('multiple'=>'multiple', 'style'=>'width:100%; height:10em; text-align:left;', 'class'=>'form-control'));
            ?>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->