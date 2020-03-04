<?php
/* @var $this UserStatusRequestController */
/* @var $model UserStatusRequest */

$this->breadcrumbs = [
    'User Status Requests' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List UserStatusRequest', 'url' => ['index']],
    ['label' => 'Create UserStatusRequest', 'url' => ['create']],
    ['label' => 'View UserStatusRequest', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage UserStatusRequest', 'url' => ['admin']],
];
?>

<h1>Update UserStatusRequest <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>