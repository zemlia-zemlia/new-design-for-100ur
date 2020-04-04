<?php
/** @var array $activeCampaigns */

$this->breadcrumbs = [
    'Выкупаемые регионы',
];

$this->pageTitle = 'Выкупаемые регионы';
Yii::app()->clientScript->registerScriptFile('/js/admin/region.js');
?>

<?php
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>

    <h1>Выкупаемые регионы</h1>

    <p>Регионы, по которым были продажи лидов за последние 3 суток</p>

<?php
// выводим виджет с ценами по регионам
$this->widget('application.widgets.RegionPrices.RegionPrices', [
    'activityIntervalDays' => 3,
    'mode' => 'regionsWithCapitals',
    'template' => 'regionsAndCapitals',
]);
?>