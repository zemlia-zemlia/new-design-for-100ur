<?php
/* @var $this CampaignController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs = [
    'Campaigns',
];

$this->menu = [
    ['label' => 'Create Campaign', 'url' => ['create']],
    ['label' => 'Manage Campaign', 'url' => ['admin']],
];
?>

<h1>Campaigns</h1>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
]); ?>
