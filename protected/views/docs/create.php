<?php
/* @var $this DocsController */
/* @var $model Docs */

$this->breadcrumbs = array(
    'Docs' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List Docs', 'url' => array('index')),
    array('label' => 'Manage Docs', 'url' => array('admin')),
);
?>

<h1>Загрузить Файл в категорию <?= $category->name ?></h1>
<div class="row">
    <div class="col-md-6">
        <?php $this->renderPartial('_form', array('model' => $model)); ?>
    </div>
    <div class="col-md-6">
    </div>
</div>
