<?php
/* @var $this UserStatusRequestController */
/* @var $model UserStatusRequest */

$this->breadcrumbs=array(
    'User Status Requests'=>array('index'),
    $model->id,
);

$this->menu=array(
    array('label'=>'List UserStatusRequest', 'url'=>array('index')),
    array('label'=>'Create UserStatusRequest', 'url'=>array('create')),
    array('label'=>'Update UserStatusRequest', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Delete UserStatusRequest', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage UserStatusRequest', 'url'=>array('admin')),
);
?>

<h1>View UserStatusRequest #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
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
    ),
)); ?>
