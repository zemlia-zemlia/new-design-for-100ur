<?php
/* @var $this RegionController */
/* @var $model Region */

$this->breadcrumbs = [
    'Regions' => ['index'],
    $model->name => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List Region', 'url' => ['index']],
    ['label' => 'Create Region', 'url' => ['create']],
    ['label' => 'View Region', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage Region', 'url' => ['admin']],
];
?>

<h1>Update Region <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>