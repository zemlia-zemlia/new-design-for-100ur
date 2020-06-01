<?php
/* @var $this QuestionCategoryController */

use App\models\QuestionCategory;

/* @var $model QuestionCategory */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerScriptFile('/js/ckeditor/ckeditor.js');
?>

<?php $form = $this->beginWidget('CActiveForm', [
    'id' => 'question-category-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => [
        'enctype' => 'multipart/form-data',
    ],
]); ?>

<div class="row">

    <div class="col-md-8">
        <div class="box">
            <div class="box-header">
                <div class="box-title">Текст стастьи</div>
            </div>
            <div class="box-body">
                <?php echo $form->errorSummary($model, 'Исправьте ошибки'); ?>

                <div class="form-group">
                    <?php echo $form->textArea($model, 'description1', [
                        'class' => 'form-control',
                        'id' => 'ckeditor',
                        'rows' => 30,
                    ]); ?>
                    <?php echo $form->error($model, 'description1'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="buttons vert-margin20">
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-block btn-primary']); ?>
        </div>

        <div class="box">
            <div class="box-body">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'name'); ?>
                    <?php echo $form->textField($model, 'name', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'name'); ?>
                </div>


                <div class="row">
                    <div class="col-md-8">

                        <div class="vert-margin30">
                            <?php echo $form->labelEx($model, 'imageFile'); ?>

                            <?php if ($model->image): ?>
                                <?php echo CHtml::image($model->getImagePath(), '', ['class' => 'img-responsive']); ?>
                            <?php endif; ?>

                            <?php echo $form->fileField($model, 'imageFile', ['class' => 'form-control']); ?>
                            <div class="length-counter"></div>
                            <?php echo $form->error($model, 'imageFile'); ?>
                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="form-group">
                            <?php echo $form->labelEx($model, 'Опубликовать:'); ?>
                            <?php $this->widget(
                        'zii.widgets.jui.CJuiDatePicker',
                        [
                                    'name' => 'QuestionCategory[publish_date]',
                                    'value' => $model['publish_date'],
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
                            <?php echo $form->error($model, 'publish_date'); ?>
                        </div>
                    </div>

                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model, 'seoTitle'); ?>
                    <?php echo $form->textField($model, 'seoTitle', ['class' => 'form-control strlen-count']); ?>
                    <div class="length-counter"></div>
                    <?php echo $form->error($model, 'seoTitle'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model, 'seoDescription'); ?>
                    <?php echo $form->textArea($model, 'seoDescription', ['class' => 'form-control strlen-count', 'rows' => 6]); ?>
                    <div class="length-counter"></div>
                    <?php echo $form->error($model, 'seoDescription'); ?>
                </div>

                <div class="form-group">
                    <?php echo $form->labelEx($model, 'seoH1'); ?>
                    <?php echo $form->textField($model, 'seoH1', ['class' => 'form-control']); ?>
                    <?php echo $form->error($model, 'seoH1'); ?>
                </div>

                <!--
        <div class="form-group">
            <?php echo $form->labelEx($model, 'seoKeywords'); ?>
            <?php echo $form->textArea($model, 'seoKeywords', ['class' => 'form-control', 'rows' => 4]); ?>
            <?php echo $form->error($model, 'seoKeywords'); ?>
        </div> -->
            </div>
        </div>

        <div class="box">
            <div class="box-body">
                <h4 class="vert-margin20">Прикрепленные файлы</h4>
                <div id="files">
                    <?php if (is_array($model->docs)):
                        foreach ($model->docs as $doc): ?>
                            <div>
                                <h6><?php echo CHtml::link(CHtml::encode($doc->name), '/admin/docs/download/?id=' . $doc->id, ['target' => '_blank']); ?>
                                    (<?php echo CHtml::encode($doc->downloads_count); ?>)
                                    <a id="deattach" data="<?php echo $doc->id; ?>" href="">открепить</a></h6>

                            </div>
                        <?php endforeach;
                    endif; ?>
                </div>
                <a class="btn btn-warning btn-block" id="attachFile" href="#">Прекрепить файлы</a>
                <?php if ($model->files): ?>
                    <p>
                        <?php $attachmentFiles = $model->files; ?>
                        <?php foreach ($attachmentFiles as $file): ?>
                            <?php echo CHtml::link($file->name, Yii::app()->urlManager->baseUrl . $file->getRelativePath(), ['target' => '_blank']); ?>
                            <?php // echo CHtml::link('удалить', '#', ['class' => 'text-danger delete-attachment-link', 'data-id' => $file->id]);?>
                            <br/>
                        <?php endforeach; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="box">
            <div class="box-body">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'parentId'); ?>
                    <?php if ($model->parent->level < 3): ?>
                        <?php echo $form->dropDownList($model, 'parentId', QuestionCategory::getCategoriesIdsNames(), ['class' => 'form-control']); ?>
                    <?php else: ?>
                        <p>
                            <?php echo $model->parent->name; ?>
                        </p>
                    <?php endif; ?>
                    <?php echo $form->error($model, 'parentId'); ?>
                </div>

                <div class="form-group checkbox">
                    <label>
                        <?php echo $form->checkBox($model, 'isDirection'); ?>
                        <?php echo $model->getAttributeLabel('isDirection'); ?>
                    </label>
                    <?php echo $form->error($model, 'parentId'); ?>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-2 center-align">
                            <?php if ($model->icon) : ?>
                                <img src="<?php echo $model->getIconUrl(); ?>" alt="<?php echo $model->name; ?>">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-10">
                            <?php echo $form->labelEx($model, 'fileIcon'); ?>
                            <?php echo $form->fileField($model, 'fileIcon', ['size' => 60, 'maxlength' => 255, 'class' => 'form-control']); ?>
                            <?php echo $form->error($model, 'fileIcon'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<?php $this->endWidget(); ?>

<script type="text/javascript">
    CKEDITOR.replace('ckeditor', {
        language: 'ru',
        height: 700,
        filebrowserBrowseUrl: '/ckfinder/ckfinder.html',
        filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
    });
</script>