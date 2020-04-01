<?php
/* @var $this DocsController */

use App\models\Docs;

/* @var $model Docs */

$this->breadcrumbs = [
    'App\models\Docs' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List App\models\Docs', 'url' => ['index']],
    ['label' => 'Manage App\models\Docs', 'url' => ['admin']],
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
