<?php
/* @var $this CampaignController */

use App\models\Campaign;

/* @var $model Campaign */

$this->breadcrumbs = [
    'Кампании' => ['index'],
    'Новая',
];

?>

<h1>Новая кампания</h1>

<?php $this->renderPartial('application.views.campaign._form', [
    'model' => $model,
    'buyersArray' => $buyersArray,
    'regions' => $regions,
    ]); ?>