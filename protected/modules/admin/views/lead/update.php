<?php
/* @var $this LeadController */

use App\models\Lead;

/* @var $model Lead */

$this->setPageTitle('Редактирование лида ' . $model->id . '. ' . Yii::app()->name);

$this->breadcrumbs = [
    'Лиды' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Редактирование',
];
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);

?>

<h1>Редактирование лида <?php echo $model->id; ?></h1>


<?php echo $this->renderPartial('_form', [
    'model' => $model,
    'allDirections' => $allDirections,
]); ?>
