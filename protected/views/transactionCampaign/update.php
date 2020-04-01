<?php
/* @var $this TransactionCampaignController */

use App\models\TransactionCampaign;

/* @var $model TransactionCampaign */

$this->breadcrumbs = [
    'Transaction Campaigns' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Update',
];

$this->menu = [
    ['label' => 'List App\models\TransactionCampaign', 'url' => ['index']],
    ['label' => 'Create App\models\TransactionCampaign', 'url' => ['create']],
    ['label' => 'View App\models\TransactionCampaign', 'url' => ['view', 'id' => $model->id]],
    ['label' => 'Manage App\models\TransactionCampaign', 'url' => ['admin']],
];
?>

<h1>Update TransactionCampaign <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>