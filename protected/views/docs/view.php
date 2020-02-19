<?php
/* @var $this DocsController */
/* @var $model Docs */

$this->breadcrumbs = [
    'Docs' => ['index'],
    $model->name,
];

$this->menu = [
    ['label' => 'List Docs', 'url' => ['index']],
    ['label' => 'Create Docs', 'url' => ['create']],
    ['label' => 'Update Docs', 'url' => ['update', 'id' => $model->id]],
    ['label' => 'Delete Docs', 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => 'Are you sure you want to delete this item?']],
    ['label' => 'Manage Docs', 'url' => ['admin']],
];
?>

<h1>View Docs #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', [
    'data' => $model,
    'attributes' => [
        'id',
        'name',
        'filename',
        'type',
        'downloads_count',
    ],
]); ?>
