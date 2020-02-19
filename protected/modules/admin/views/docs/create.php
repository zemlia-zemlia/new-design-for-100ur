<?php
/* @var $this DocsController */
/* @var $model Docs */

$this->breadcrumbs = [
    'Docs' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List Docs', 'url' => ['index']],
    ['label' => 'Manage Docs', 'url' => ['admin']],
];
?>

<h1>Загрузить Файл в категорию <?php echo $category->name; ?></h1>
<div class="row">
    <div class="col-md-6">
        <?php $this->renderPartial('_form', ['model' => $model]); ?>
    </div>
    <div class="col-md-6">
    </div>
</div>
