<?php
/* @var $this FileController */
/* @var $model File */

$this->breadcrumbs = [
    'Files' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List File', 'url' => ['index']],
    ['label' => 'Create File', 'url' => ['create']],
    ['label' => 'View File', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage File', 'url' => ['admin']],
];
?>

<h1>Update File <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>