<?php
/* @var $this FileController */

use App\models\File;

/* @var $model File */

$this->breadcrumbs = [
    'Files' => ['index'],
    $model->name,
];

$this->menu = [
    ['label' => 'List File', 'url' => ['index']],
    ['label' => 'Create File', 'url' => ['create']],
    ['label' => 'Update File', 'url' => ['update', 'id' => $model->id]],
    ['label' => 'Delete File', 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => 'Are you sure you want to delete this item?']],
    ['label' => 'Manage File', 'url' => ['admin']],
];
?>

<h1>View File #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', [
    'data' => $model,
    'attributes' => [
        'id',
        'name',
        'filename',
        'objectId',
        'objectType',
        'type',
    ],
]); ?>
