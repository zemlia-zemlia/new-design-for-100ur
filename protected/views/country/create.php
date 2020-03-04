<?php
/* @var $this CountryController */
/* @var $model Country */

$this->breadcrumbs = [
    'Countries' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List Country', 'url' => ['index']],
    ['label' => 'Manage Country', 'url' => ['admin']],
];
?>

<h1>Create Country</h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>