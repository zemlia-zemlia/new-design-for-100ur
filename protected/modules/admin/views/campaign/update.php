<?php
/* @var $this CampaignController */

use App\models\Campaign;

/* @var $model Campaign */

$this->breadcrumbs = [
    'Кампании' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Редактирование',
];

$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 юристов', '/admin'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
 ]);

?>

<h1>Редактирование кампании <?php echo $model->id; ?></h1>

<?php $this->renderPartial('application.views.campaign._form', [
    'model' => $model,
    'buyersArray' => $buyersArray,
    'regions' => $regions,
    ]); ?>