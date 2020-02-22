<?php
/* @var $this FileCategoryController */
/* @var $model FileCategory */

$this->breadcrumbs = [
    'File Categories' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List FileCategory', 'url' => ['index']],
    ['label' => 'Create FileCategory', 'url' => ['create']],
    ['label' => 'View FileCategory', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage FileCategory', 'url' => ['admin']],
];
?>

<h1>Update FileCategory <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>