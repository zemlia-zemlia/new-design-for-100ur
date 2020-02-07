<?php
/* @var $this DocsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = array(
    'Docs',
);


foreach (Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
}


$this->menu = array(
    array('label' => 'Create Docs', 'url' => array('create')),
    array('label' => 'Manage Docs', 'url' => array('admin')),
);
?>
<?php //var_dump($this->menu);die;?>

<h1>Хранилище образцов документов</h1>

<div class="row">
    <div class="col-md-8">
        <div class="box">
            <div class="box-body">
                <div id="categories">
                    <?php $this->renderPartial('_table', ['categories' => $categories]); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box">
            <div class="box-body">
                <a class="btn btn-warning btn-block" id="addCategory" href="/admin/file-category/create/?cat_id=0">Добавить
                    корневую
                    категорию</a>
            </div>
        </div>
    </div>
</div>

<?php
if ($category)
    $this->renderPartial('_table_files', ['files' => $category->files]);
?>

