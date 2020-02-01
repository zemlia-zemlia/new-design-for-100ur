<?php
/* @var $this TransactionCampaignController */
/* @var $model TransactionCampaign */

$this->breadcrumbs=array(
    'Transaction Campaigns'=>array('index'),
    'Create',
);

$this->menu=array(
    array('label'=>'List TransactionCampaign', 'url'=>array('index')),
    array('label'=>'Manage TransactionCampaign', 'url'=>array('admin')),
);
?>

<h1>Create TransactionCampaign</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>