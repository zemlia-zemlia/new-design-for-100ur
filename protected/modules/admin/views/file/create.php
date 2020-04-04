<?php
/* @var $this FileController */

use App\models\File;

/* @var $model File */

$this->breadcrumbs = [
    'Files' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List File', 'url' => ['index']],
    ['label' => 'Manage File', 'url' => ['admin']],
];
?>

<h1>Create File</h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>