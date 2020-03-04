<?php
/* @var $this CodecsController */
/* @var $model Codecs */

$this->breadcrumbs = [
    'Codecs' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List Codecs', 'url' => ['index']],
    ['label' => 'Manage Codecs', 'url' => ['admin']],
];
?>

<h1>Create Codecs</h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>