<?php
/* @var $this DocsController */
/* @var $model Docs */

$this->breadcrumbs=array(
	'Docs'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);


?>

<h1>Редактировать файл <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
<?php if (!$model->isNewRecord): ?>
<p>Скачать:
<a  target="_blank" href="/docs/download/?id=<?= $model->id ?>"><?= $model->name?>(<?php echo CHtml::encode($model->downloads_count); ?>)</a>
</p>
<?php endif; ?>