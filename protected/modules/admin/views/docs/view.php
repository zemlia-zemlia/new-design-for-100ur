<?php
/* @var $this DocsController */

use App\models\Docs;

/* @var $model Docs */

$this->breadcrumbs = [
    'App\models\Docs' => ['index'],
    $model->name,
];

$this->menu = [
    ['label' => 'List App\models\Docs', 'url' => ['index']],
    ['label' => 'Create App\models\Docs', 'url' => ['create']],
    ['label' => 'Update App\models\Docs', 'url' => ['update', 'id' => $model->id]],
    ['label' => 'Delete App\models\Docs', 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => 'Are you sure you want to delete this item?']],
    ['label' => 'Manage App\models\Docs', 'url' => ['admin']],
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
