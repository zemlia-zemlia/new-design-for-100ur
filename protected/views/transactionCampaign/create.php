<?php
/* @var $this TransactionCampaignController */
/* @var $model TransactionCampaign */

$this->breadcrumbs = [
    'Transaction Campaigns' => ['index'],
    'Create',
];

$this->menu = [
    ['label' => 'List TransactionCampaign', 'url' => ['index']],
    ['label' => 'Manage TransactionCampaign', 'url' => ['admin']],
];
?>

<h1>Create TransactionCampaign</h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>