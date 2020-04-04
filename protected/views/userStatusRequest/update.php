<?php
/* @var $this UserStatusRequestController */

use App\models\UserStatusRequest;

/* @var $model UserStatusRequest */

$this->breadcrumbs = [
    'User Status Requests' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List App\models\UserStatusRequest', 'url' => ['index']],
    ['label' => 'Create App\models\UserStatusRequest', 'url' => ['create']],
    ['label' => 'View App\models\UserStatusRequest', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage App\models\UserStatusRequest', 'url' => ['admin']],
];
?>

<h1>Update UserStatusRequest <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>