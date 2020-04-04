<?php
/* @var $this TransactionCampaignController */

use App\models\TransactionCampaign;

/* @var $model TransactionCampaign */

$this->breadcrumbs = [
    'Transaction Campaigns' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List App\models\TransactionCampaign', 'url' => ['index']],
    ['label' => 'Manage App\models\TransactionCampaign', 'url' => ['admin']],
];
?>

<h1>Create TransactionCampaign</h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>