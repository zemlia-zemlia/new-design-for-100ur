<?php
/* @var $this FileCategoryController */
/* @var $model FileCategory */

$this->breadcrumbs = array(
    'Категория' => array('index'),
    $model->name,
);

$this->breadcrumbs = array(
    'Хранилище документов' => array('/docs/'),
    $model->name,
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('Админка', "/admin/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));

$this->menu = array(
    array('label' => 'List FileCategory', 'url' => array('index')),
    array('label' => 'Create FileCategory', 'url' => array('create')),
    array('label' => 'Update FileCategory', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete FileCategory', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage FileCategory', 'url' => array('admin')),
);
Yii::app()->clientScript->registerScript('check', "
$('#removeCategory').click(function(){
$('#exampleModal').modal('show');
});

", CClientScript::POS_END);


?>

<h2>Категория файлов: <?php echo $model->name; ?></h2>

<div class="row">
    <div class="col-md-8">
        <div class="box box-info">
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
        <div class="box box-warning">
            <div class="box-header">
                <div class="box-title">
                    Файлы вложенные в эту категорию
                </div>
            </div>
            <div class="box-body">
                <div class="vert-margin10">
                <?php
                if (is_array($model->files))
                    $this->renderPartial('/docs/_table_files', ['files' => $model->files]);
                ?>
                </div>
                <a class="btn btn-warning center-block" id="updateFile" href="/admin/docs/create/?id=<?= $model->id ?>">Добавить новый файл</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header">
                <div class="box-title">
                    Инфо о категории
                </div>
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <?php if ($model->parentObj): ?>
                        <tr>
                            <td>Родитель:</td>
                            <td>
                                <a href="/admin/file-category/view/?id=<?= $model->parentObj->id ?>"> <?= $model->parentObj->name ?></a>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td>Название категории:</td>
                        <td><?= $model->name; ?></td>
                    </tr>
                    <tr>
                        <td>Описание категории:</td>
                        <td><?= $model->description; ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="box box-primary">
            <div class="box-header">
                <div class="box-title">
                    Управление
                </div>
            </div>
            <div class="box-body">
                <a class="btn btn-info btn-block" id="updateCategory"
                   href="/admin/file-category/update/?id=<?= $model->id ?>">Редактировать категорию</a>
                <a class="btn btn-primary btn-block" id="addCategory"
                   href="/admin/file-category/create/?id=<?= $model->id ?>">Добавить вложенную категорию</a>
                <button class="btn btn-danger btn-block btn-xs" id="removeCategory">Удалить категорию</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
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
                <button type="button" onclick="$('#exampleModal').modal('hide')" class="btn btn-primary">Нет</button>
                <a href="/admin/file-category/delete/?id=<?= $model->id ?>" class="btn btn-primary">Да</a>
            </div>
        </div>
    </div>
</div>


