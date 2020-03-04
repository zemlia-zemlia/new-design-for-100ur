<?php
/* @var $this CountryController */
/* @var $model Country */

$this->breadcrumbs = [
    'Countries' => ['index'],
    $model->name,
];

$this->menu = [
    ['label' => 'List Country', 'url' => ['index']],
    ['label' => 'Create Country', 'url' => ['create']],
    ['label' => 'Update Country', 'url' => ['update', 'id' => $model->id]],
    ['label' => 'Delete Country', 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => 'Are you sure you want to delete this item?']],
    ['label' => 'Manage Country', 'url' => ['admin']],
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
