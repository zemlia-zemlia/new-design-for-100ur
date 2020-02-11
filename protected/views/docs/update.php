<?php
/* @var $this DocsController */
/* @var $model Docs */

$this->breadcrumbs = array(
    'Docs' => array('index'),
    $model->name => array('view', 'id' => $model->id),
    'Update',
);

Yii::app()->clientScript->registerScript('delete', "
$('#delete').click(function(){
alert('Серьезно?');
});
");
?>



<h1>Редактировать файл <?php echo $model->name; ?></h1>
<div class="row">
    <div class="col-md-6">
        <?php $this->renderPartial('_form', array('model' => $model)); ?>

    </div>
    <div class="col-md-6">
        <?php if (!$model->isNewRecord): ?>
            <p>Скачать:
                <a target="_blank" href="/docs/download/?id=<?= $model->id ?>"><?= $model->name ?>
                    (<?php echo CHtml::encode($model->downloads_count); ?>)</a>
            </p>
        <?php endif; ?>
    </div>
</div>
