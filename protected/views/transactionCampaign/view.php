<?php
/* @var $this TransactionCampaignController */
/* @var $model TransactionCampaign */

$this->breadcrumbs = [
    'Transaction Campaigns' => ['index'],
    $model->id,
];

$this->menu = [
    ['label' => 'List TransactionCampaign', 'url' => ['index']],
    ['label' => 'Create TransactionCampaign', 'url' => ['create']],
    ['label' => 'Update TransactionCampaign', 'url' => ['update', 'id' => $model->id]],
    ['label' => 'Delete TransactionCampaign', 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => 'Are you sure you want to delete this item?']],
    ['label' => 'Manage TransactionCampaign', 'url' => ['admin']],
];
?>

<h1>View TransactionCampaign #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', [
    'data' => $model,
    'attributes' => [
        'id',
        'campaignId',
        'time',
        'sum',
        'description',
    ],
]); ?>
