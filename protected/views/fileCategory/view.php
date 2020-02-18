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
Yii::app()->clientScript->registerScript('check', "
$('#removeCategory').click(function(){
$('#exampleModal').modal('show');
});

", CClientScript::POS_END);


?>

<h1>Категория  <?php echo $model->name; ?></h1>

<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header">
                <div class="box-title">
                    Вложенные категории
                </div>
            </div>
            <div class="box-body">
                <?php
                if (is_array($model->children()->findAll()))
                    $this->renderPartial('/docs/_table', ['categories' => $model->children()->findAll()]);
                ?>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <div class="box-title">
                    Файлы категории
                </div>
            </div>
            <div class="box-body">
                <?php
                if (is_array($model->files))
                    $this->renderPartial('/docs/_table_files', ['files' => $model->files]);
                ?>
                <a class="btn btn-warning right-align"  id="updateFile" href="/admin/docs/create/?id=<?= $model->id ?>">Добавить файл</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box">
            <div class="box-header">
                <div class="box-title">
                    Инфо о категории
                </div>
            </div>
            <div class="box-body">
                <?php if ($model->parentObj): ?>
                Родитель: <a href="/admin/file-category/view/?id=<?= $model->parentObj->id ?>"> <?= $model->parentObj->name ?></a>
     <?php endif; ?>
                <?php $this->widget('zii.widgets.CDetailView', array(
                    'data'=>$model,
                    'attributes'=>array(
                            [
                            'attribute' => 'parentObj',
                                'format' => 'html',
                                'value' => function($data){
                                return '<a href="/admin/file-category/view/?id=' . $data->parentObj->id . '">' . $data->parentObj->name .' </a>';
                                }
],

                        'name',
                        'description',

                    ),
                )); ?>
            </div>
        </div>

        <div class="box">
            <div class="box-header">
                <div class="box-title">
                    Управление
                </div>
            </div>
            <div class="box-body">
                <a class="btn btn-info btn-block"  id="updateCategory" href="/admin/file-category/update/?id=<?= $model->id ?>">Редактировать категорию</a>
                <a class="btn btn-primary btn-block"  id="addCategory" href="/admin/file-category/create/?id=<?= $model->id ?>">Добавить вложенную категорию</a>
                <button class="btn btn-danger btn-block btn-xs"  id="removeCategory" >Удалить категорию</button>
            </div>
        </div>
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
                <a  href="/admin/file-category/delete/?id=<?= $model->id ?>" class="btn btn-primary">Да</a>
            </div>
        </div>
    </div>
</div>


