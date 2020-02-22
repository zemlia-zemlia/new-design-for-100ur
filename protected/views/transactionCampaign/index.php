<?php
/* @var $this TransactionCampaignController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'Transaction Campaigns',
];

$this->menu = [
    ['label' => 'Create TransactionCampaign', 'url' => ['create']],
    ['label' => 'Manage TransactionCampaign', 'url' => ['admin']],
];
?>

<h1>Transaction Campaigns</h1>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
]); ?>
