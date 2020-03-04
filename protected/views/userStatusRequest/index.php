<?php
/* @var $this UserStatusRequestController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'User Status Requests',
];

$this->menu = [
    ['label' => 'Create UserStatusRequest', 'url' => ['create']],
    ['label' => 'Manage UserStatusRequest', 'url' => ['admin']],
];
?>

<h1>User Status Requests</h1>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
]); ?>
