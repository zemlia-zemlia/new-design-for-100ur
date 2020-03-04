<?php
/* @var $this PostController */
/* @var $model Post */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerScriptFile('/js/ckeditor/ckeditor.js');
?>

<?php
$form = $this->beginWidget('CActiveForm', [
    'id' => 'post-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => [
        'enctype' => 'multipart/form-data',
    ],
]);
?>
<?php echo $form->errorSummary($model); ?>

<div class="row">

    <div class="col-sm-8">

        <div class="form-group">
            <?php echo $form->labelEx($model, 'title'); ?>
            <?php echo $form->textField($model, 'title', ['class' => 'form-control', 'maxlength' => 256]); ?>
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
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <?php echo $form->labelEx($model, 'datePublication'); ?>
            <?php
            $this->widget(
                'zii.widgets.jui.CJuiDatePicker',
                [
                    'name' => 'Post[datePublication]',
                    'value' => $model['datePublication'],
                    'language' => 'ru',
                    'options' => ['dateFormat' => 'dd-mm-yy',
                    ],
                    'htmlOptions' => [
                        'style' => 'text-align:right;',
                        'class' => 'form-control',
                    ],
                ]
            );
            ?>
            <?php echo $form->error($model, 'datePublication'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'description'); ?>
            <?php echo $form->textArea($model, 'description', ['class' => 'form-control', 'rows' => 5]); ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>

        <?php if ($model->photo):?>
            <img src="<?php echo $model->getPhotoUrl(); ?>" class="img-responsive" />
        <?php endif; ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'photoFile'); ?>
            <?php echo $form->fileField($model, 'photoFile'); ?>
            <?php echo $form->error($model, 'photoFile'); ?>
        </div>
    </div>
</div><!-- form -->

<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать пост' : 'Сохранить пост', ['class' => 'btn btn-primary btn-large']); ?>
<?php
if (!$model->isNewRecord) {
                echo CHtml::link('Не сохранять', Yii::app()->createUrl('/admin/post/view', ['id' => $model->id]), ['class' => 'btn btn-large']);
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