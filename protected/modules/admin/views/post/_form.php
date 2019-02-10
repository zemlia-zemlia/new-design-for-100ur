<?php
/* @var $this PostController */
/* @var $model Post */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerScriptFile('/js/ckeditor/ckeditor.js');
?>

<div style="width: 100%;">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'post-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array(
            'enctype' => 'multipart/form-data',
        ),
    ));
    ?>

    <p class="note"><span class="required">*</span> обязательные поля</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'datePublication'); ?>
        <?php
        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => "Post[datePublication]",
                'value' => $model['datePublication'],
                'language' => 'ru',
                'options' => array('dateFormat' => 'dd-mm-yy',
                ),
                'htmlOptions' => array(
                    'style' => 'text-align:right;',
                    'class' => 'form-control'
                )
            )
        );
        ?>
        <?php echo $form->error($model, 'datePublication'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('class' => 'form-control', 'maxlength' => 256)); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'preview'); ?>

        <?php echo $form->textArea($model, 'preview', [
            'class' => 'form-control',
            'id' => 'ckeditor-preview',
            'rows' => 5,
        ]); ?>

        <?php echo $form->error($model, 'preview'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'text'); ?>

        <?php echo $form->textArea($model, 'text', [
            'class' => 'form-control',
            'id' => 'ckeditor',
            'rows' => 20,
        ]); ?>
        <?php echo $form->error($model, 'text'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php
        echo $form->textArea($model, 'description', array('class' => 'form-control'));
        ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'photoFile'); ?>
        <?php echo $form->fileField($model, 'photoFile'); ?>
        <?php echo $form->error($model, 'photoFile'); ?>
    </div>


    <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать пост' : 'Сохранить пост', array('class' => 'btn btn-primary btn-large')); ?>
    <?php
    if (!$model->isNewRecord) {
        echo CHtml::link('Не сохранять', Yii::app()->createUrl('/admin/post/view', array('id' => $model->id)), array('class' => 'btn btn-large'));
    }
    ?>


    <?php $this->endWidget(); ?>

    <script type="text/javascript">
        CKEDITOR.replace('ckeditor-preview', {
            language: 'ru',
            height: 200,
            filebrowserBrowseUrl: '/ckfinder/ckfinder.html',
            filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
        });
        CKEDITOR.replace('ckeditor', {
            language: 'ru',
            height: 400,
            filebrowserBrowseUrl: '/ckfinder/ckfinder.html',
            filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
        });
    </script>

</div><!-- form -->