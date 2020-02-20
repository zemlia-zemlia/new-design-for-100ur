<?php
/* @var $this DocsController */
/* @var $dataProvider CActiveDataProvider */

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
                <a class="btn btn-warning btn-block" id="addCategory" href="/admin/file-category/create/?cat_id=0"><i class="fa fa-plus" aria-hidden="true"></i>
                     Добавить корневую категорию</a>
            </div>
        </div>
    </div>
</div>

<?php
if ($category)
    $this->renderPartial('_table_files', ['files' => $category->files]);
?>

