<?php
/* @var $this FileCategoryController */
/* @var $model FileCategory */

$this->breadcrumbs = array(
    'File Categories' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List FileCategory', 'url' => array('index')),
    array('label' => 'Manage FileCategory', 'url' => array('admin')),
);
?>

<h1>Новая категория файлов</h1>
<div class="row">
    <div class="col-md-6">
        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>

    <div class="col-md-6">
    </div>
</div>
