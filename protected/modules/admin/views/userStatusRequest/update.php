<?php
/* @var $this UserStatusRequestController */
/* @var $model UserStatusRequest */

$this->breadcrumbs=array(
    'User Status Requests'=>array('index'),
    $model->id=>array('view','id'=>$model->id),
    'Update',
);

$this->menu=array(
    array('label'=>'List UserStatusRequest', 'url'=>array('index')),
    array('label'=>'Create UserStatusRequest', 'url'=>array('create')),
    array('label'=>'View UserStatusRequest', 'url'=>array('view', 'id'=>$model->id)),
    array('label'=>'Manage UserStatusRequest', 'url'=>array('admin')),
);
?>

<h1>Update UserStatusRequest <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>