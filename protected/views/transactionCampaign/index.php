<?php
/* @var $this TransactionCampaignController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'Transaction Campaigns',
];

$this->menu = [
    ['label' => 'Create App\models\TransactionCampaign', 'url' => ['create']],
    ['label' => 'Manage App\models\TransactionCampaign', 'url' => ['admin']],
];
?>

<h1>Transaction Campaigns</h1>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
]); ?>
