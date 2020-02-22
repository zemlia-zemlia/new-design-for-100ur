<?php
/* @var $this DocsController */
/* @var $dataProvider CActiveDataProvider */

<<<<<<< HEAD:protected/views/docs/index.php
$this->breadcrumbs = array(
    'Хранилище образцов документов',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('Админка', "/admin/"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
));

$this->menu = array(
    array('label' => 'Create Docs', 'url' => array('create')),
    array('label' => 'Manage Docs', 'url' => array('admin')),
);
=======
$this->breadcrumbs = [
    'Docs',
];

$this->menu = [
    ['label' => 'Create Docs', 'url' => ['create']],
    ['label' => 'Manage Docs', 'url' => ['admin']],
];
>>>>>>> 667263e81cd3b63052977713d7a6346292116b19:protected/modules/admin/views/docs/index.php
?>
<?php //var_dump($this->menu);die;?>

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
<<<<<<< HEAD:protected/views/docs/index.php
                <a class="btn btn-warning btn-block" id="addCategory" href="/admin/file-category/create/?cat_id=0"><i class="fa fa-plus" aria-hidden="true"></i>
                     Добавить корневую категорию</a>
=======
                <a class="btn btn-warning btn-block" id="addCategory" href="<?= Yii::app()->createUrl('/admin/fileCategory/create', ['id' => 0]); ?>">Добавить
                    корневую
                    категорию</a>
>>>>>>> 667263e81cd3b63052977713d7a6346292116b19:protected/modules/admin/views/docs/index.php
            </div>
        </div>
    </div>
</div>

<?php
if ($category) {
    $this->renderPartial('_table_files', ['files' => $category->files]);
}
?>

