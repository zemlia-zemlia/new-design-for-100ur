<?php
/* @var $this TransactionCampaignController */
/* @var $model TransactionCampaign */

$this->breadcrumbs = [
    'Transaction Campaigns' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List TransactionCampaign', 'url' => ['index']],
    ['label' => 'Create TransactionCampaign', 'url' => ['create']],
    ['label' => 'View TransactionCampaign', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage TransactionCampaign', 'url' => ['admin']],
];
?>

<h1>Update TransactionCampaign <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>