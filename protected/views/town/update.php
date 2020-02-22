<?php
/* @var $this TownController */
/* @var $model Town */

$this->breadcrumbs = [
    'Towns' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List Town', 'url' => ['index']],
    ['label' => 'Create Town', 'url' => ['create']],
    ['label' => 'View Town', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage Town', 'url' => ['admin']],
];
?>

<h1>Update Town <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>