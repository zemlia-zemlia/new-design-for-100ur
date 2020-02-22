<?php
/* @var $this RegionController */
/* @var $model Region */

$this->breadcrumbs = [
    'Regions' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List Region', 'url' => ['index']],
    ['label' => 'Manage Region', 'url' => ['admin']],
];
?>

<h1>Create Region</h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>