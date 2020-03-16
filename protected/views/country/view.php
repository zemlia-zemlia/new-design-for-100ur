<?php
/* @var $this CountryController */

use App\models\Country;

/* @var $model Country */

$this->breadcrumbs = [
    'Countries' => ['index'],
    $model->name,
];

$this->menu = [
    ['label' => 'List App\models\Country', 'url' => ['index']],
    ['label' => 'Create App\models\Country', 'url' => ['create']],
    ['label' => 'Update App\models\Country', 'url' => ['update', 'id' => $model->id]],
    ['label' => 'Delete App\models\Country', 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => 'Are you sure you want to delete this item?']],
    ['label' => 'Manage App\models\Country', 'url' => ['admin']],
];
?>

<h1>View Country #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', [
    'data' => $model,
    'attributes' => [
        'id',
        'name',
        'alias',
    ],
]); ?>
