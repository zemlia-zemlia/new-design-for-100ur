<?php
/* @var $this UserStatusRequestController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'User Status Requests',
);

$this->menu=array(
    array('label'=>'Create UserStatusRequest', 'url'=>array('create')),
    array('label'=>'Manage UserStatusRequest', 'url'=>array('admin')),
);
?>

<h1>User Status Requests</h1>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
)); ?>
