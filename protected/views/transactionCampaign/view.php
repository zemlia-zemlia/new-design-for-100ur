<?php
/* @var $this TransactionCampaignController */
/* @var $model TransactionCampaign */

$this->breadcrumbs=array(
    'Transaction Campaigns'=>array('index'),
    $model->id,
);

$this->menu=array(
    array('label'=>'List TransactionCampaign', 'url'=>array('index')),
    array('label'=>'Create TransactionCampaign', 'url'=>array('create')),
    array('label'=>'Update TransactionCampaign', 'url'=>array('update', 'id'=>$model->id)),
    array('label'=>'Delete TransactionCampaign', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'Manage TransactionCampaign', 'url'=>array('admin')),
);
?>

<h1>View TransactionCampaign #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id',
        'campaignId',
        'time',
        'sum',
        'description',
    ),
)); ?>
