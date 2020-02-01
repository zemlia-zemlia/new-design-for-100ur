<?php
/* @var $this TransactionCampaignController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'Transaction Campaigns',
);

$this->menu=array(
    array('label'=>'Create TransactionCampaign', 'url'=>array('create')),
    array('label'=>'Manage TransactionCampaign', 'url'=>array('admin')),
);
?>

<h1>Transaction Campaigns</h1>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider'=>$dataProvider,
    'itemView'=>'_view',
)); ?>
