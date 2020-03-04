<?php
/* @var $this UserStatusRequestController */
/* @var $model UserStatusRequest */

$this->breadcrumbs = [
    'User Status Requests' => ['index'],
    $model->id,
];

$this->menu = [
    ['label' => 'List UserStatusRequest', 'url' => ['index']],
    ['label' => 'Create UserStatusRequest', 'url' => ['create']],
    ['label' => 'Update UserStatusRequest', 'url' => ['update', 'id' => $model->id]],
    ['label' => 'Delete UserStatusRequest', 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => 'Are you sure you want to delete this item?']],
    ['label' => 'Manage UserStatusRequest', 'url' => ['admin']],
];
?>

<h1>View UserStatusRequest #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', [
    'data' => $model,
    'attributes' => [
        'id',
        'yuristId',
        'status',
        'isVerified',
        'vuz',
        'facultet',
        'education',
        'vuzTownId',
        'educationYear',
        'advOrganisation',
        'advNumber',
        'position',
    ],
]); ?>
