<?php
/* @var $this TownController */
/* @var $model Town */

$this->breadcrumbs = [
    'Towns' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List Town', 'url' => ['index']],
    ['label' => 'Manage Town', 'url' => ['admin']],
];
?>

<h1>Create Town</h1>

<?php echo $this->renderPartial('_form', ['model' => $model]); ?>