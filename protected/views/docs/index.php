<?php
/* @var $this DocsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Docs',
);



    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="alert alert-' . $key . '">' . $message . "</div>\n";
    }




$this->menu=array(
	array('label'=>'Create Docs', 'url'=>array('create')),
	array('label'=>'Manage Docs', 'url'=>array('admin')),
);
?>
<?php //var_dump($this->menu);die;?>







<h1>Файлы</h1>
<a class="btn btn-primary" id="addFile" href="#">Загрузить в категорию (выберите категорию)</a>





<div id="categories">
    <?php $this->renderPartial('_table', ['categories' => $categories]); ?>


</div>

<div class="row">
    <div class="col-lg-12">

        <a class="btn btn-warning"  id="addCategory" href="/fileCategory/create/?cat_id=0">Добавить корневую категорию</a>
    </div>
</div>

<hr>

<?php
if ($category)
$this->renderPartial('_table_files', ['files' => $category->files]);
?>

