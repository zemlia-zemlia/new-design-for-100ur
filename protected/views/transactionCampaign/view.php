<?php
/* @var $this TransactionCampaignController */

use App\models\TransactionCampaign;

/* @var $model TransactionCampaign */

$this->breadcrumbs = [
    'Transaction Campaigns' => ['index'],
    $model->id,
];

$this->menu = [
    ['label' => 'List App\models\TransactionCampaign', 'url' => ['index']],
    ['label' => 'Create App\models\TransactionCampaign', 'url' => ['create']],
    ['label' => 'Update App\models\TransactionCampaign', 'url' => ['update', 'id' => $model->id]],
    ['label' => 'Delete App\models\TransactionCampaign', 'url' => '#', 'linkOptions' => ['submit' => ['delete', 'id' => $model->id], 'confirm' => 'Are you sure you want to delete this item?']],
    ['label' => 'Manage App\models\TransactionCampaign', 'url' => ['admin']],
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
