<?php
/* @var $this FileCategoryController */
/* @var $model FileCategory */

$this->breadcrumbs=array(
	'Категория'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List FileCategory', 'url'=>array('index')),
	array('label'=>'Create FileCategory', 'url'=>array('create')),
	array('label'=>'Update FileCategory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete FileCategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage FileCategory', 'url'=>array('admin')),
);
?>

<h1>Категория  <?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(

		'name',
		'description',

	),
)); ?>

<a class="btn btn-primary"  id="addCategory" href="/admin/file-category/create/?id=<?= $model->id ?>">Добавить категорию</a>
<a class="btn btn-danger"  id="removeCategory" href="/admin/file-category/delete/?id=<?= $model->id ?>">Удалить категорию</a>
<a class="btn btn-danger"  id="updateCategory" href="/admin/file-category/update/?id=<?= $model->id ?>">Редактировать категорию</a>

<hr>
<h3>Подкатегории</h3>

<?php
if (is_array($model->children()->findAll()))
    $this->renderPartial('/docs/_table', ['categories' => $model->children()->findAll()]);
?>

<hr>
<h3>Файлы</h3>
<?php
if (is_array($model->files))
    $this->renderPartial('/docs/_table_files', ['files' => $model->files]);
?>
<a class="btn btn-danger"  id="updateFile" href="/admin/docs/create/?id=<?= $model->id ?>">Добавить файл</a>