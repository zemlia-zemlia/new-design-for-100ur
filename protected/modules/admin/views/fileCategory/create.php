<?php
/* @var $this FileCategoryController */

use App\models\FileCategory;

/* @var $model FileCategory */

$this->breadcrumbs = [
    'File Categories' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List FileCategory', 'url' => ['index']],
    ['label' => 'Manage FileCategory', 'url' => ['admin']],
];
?>

<h1>Новая категория файлов</h1>
<div class="row">
    <div class="col-md-6">
        <?php $this->renderPartial('_form', ['model' => $model]); ?>
    </div>

    <div class="col-md-6">
    </div>
</div>
