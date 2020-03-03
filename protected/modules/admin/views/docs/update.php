<?php
/* @var $this DocsController */
/* @var $model Docs */

$this->breadcrumbs = [
    'Docs' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Update',
];

?>



<h1>Редактировать файл <?php echo $model->name; ?></h1>
<div class="row">
    <div class="col-md-6">
        <?php $this->renderPartial('_form', ['model' => $model]); ?>

    </div>
    <div class="col-md-6">
        <?php if (!$model->isNewRecord): ?>
            <p>Скачать:
                <a target="_blank" href="<?php echo Yii::app()->createUrl('/docs/download', ['id' => $model->id]); ?>"><?php echo $model->name; ?>
                    (<?php echo CHtml::encode($model->downloads_count); ?>)</a>
            </p>
        <?php endif; ?>
    </div>
</div>
