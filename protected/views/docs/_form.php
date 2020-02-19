<?php
/* @var $this DocsController */
/* @var $model Docs */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerScript('check', "
$('#delete').click(function(){
$('#exampleModal').modal('show');
});





//$('#docs_form').beforeSubmit(function(e){
//if ($('#Docs_name.val() == '' ){
//alert('заполните название файла');
//e.preventDefault;
//}
//});
", CClientScript::POS_END);
?>


<div class="box">
    <div class="box-body">
        <div class="form">

            <?php $form = $this->beginWidget('CActiveForm', [
                'id' => 'docs-form',
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation' => false,
                'htmlOptions' => ['enctype' => 'multipart/form-data'],
            ]); ?>

            <?php echo $form->errorSummary($model); ?>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'name'); ?>
                <?php echo $form->textField($model, 'name', ['size' => 60, 'maxlength' => 255, 'class' => 'form-control']); ?>
                <?php echo $form->error($model, 'name'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'description'); ?>
                <?php echo $form->textArea($model, 'description', ['rows' => 6, 'class' => 'form-control']); ?>
                <?php echo $form->error($model, 'description'); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model, 'file'); ?>

                <?php echo $form->fileField($model, 'file', ['size' => 60, 'maxlength' => 255, 'class' => 'form-control']); ?>
                <?php echo $form->error($model, 'file'); ?>
            </div>




            <div class="form-group buttons">
                <?php echo CHtml::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
                <?php if (!$model->isNewRecord): ?>
                <button type="button"  id="delete" class="btn btn-warning">Удалить</button>
                <?php endif; ?>



            </div>
            <p class="text-muted">Ограничения по размеру - 10 мб, <br>типы файлов - doc, docx, pdf, csv, xlsx, xls, rar, zip, 7z.</p>

            <div class="form-group">
                <p>Количество скачиваний: <?php echo $model->downloads_count; ?></p>
            </div>

            <?php $this->endWidget(); ?>

        </div><!-- form -->
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>Серьезно?</h6>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="$('#exampleModal').modal('hide')"   class="btn btn-primary">Нет</button>
                <a href="/docs/delete/?id=<?php echo $model->id; ?>"  class="btn btn-primary">Да</a>
            </div>
        </div>
    </div>
</div>