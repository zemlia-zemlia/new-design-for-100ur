<?php
/* @var $this TransactionCampaignController */
/* @var $model TransactionCampaign */

$this->breadcrumbs=array(
    'Transaction Campaigns'=>array('index'),
    $model->id=>array('view','id'=>$model->id),
    'Update',
);

$this->menu=array(
    array('label'=>'List TransactionCampaign', 'url'=>array('index')),
    array('label'=>'Create TransactionCampaign', 'url'=>array('create')),
    array('label'=>'View TransactionCampaign', 'url'=>array('view', 'id'=>$model->id)),
    array('label'=>'Manage TransactionCampaign', 'url'=>array('admin')),
);
?>

<h1>Update TransactionCampaign <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>