<?php
/* @var $this DocsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'Хранилище образцов документов',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('Админка', '/admin/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);

$this->menu = [
    ['label' => 'Create Docs', 'url' => ['create']],
    ['label' => 'Manage Docs', 'url' => ['admin']],
];

?>


<h2>Хранилище образцов документов</h2>
<div class="row">
    <div class="col-md-9">
        <div class="box">
            <div class="box-header">
                <div class="box-title">Категории документов</div>
            </div>
            <div class="box-body">
                <div id="categories">
                    <?php $this->renderPartial('_table', ['categories' => $categories]); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="box">
            <div class="box-body">
                <a class="btn btn-warning btn-block" id="addCategory" href="<?= Yii::app()->createUrl('/admin/fileCategory/create') ?>"><i class="fa fa-plus" aria-hidden="true"></i>
                     Добавить корневую категорию</a>
            </div>
        </div>
    </div>
</div>

<?php
if ($category) {
    $this->renderPartial('_table_files', ['files' => $category->files]);
}
?>

