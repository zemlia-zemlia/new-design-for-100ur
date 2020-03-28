<?php
/* @var $this RegionController */
/* @var $dataProvider CActiveDataProvider */
/* @var $regions array */


$pageTitle = 'Управление регионами';

$this->setPageTitle($pageTitle);

Yii::app()->clientScript->registerMetaTag('Каталог Юристов и Адвокатов России и СНГ', 'Description');
Yii::app()->clientScript->registerScriptFile('/js/admin/region.js');

$this->breadcrumbs = [
    'Цены на выкуп лидов по регионам',
];

?>

<?php
$this->widget('zii.widgets.CBreadcrumbs', [
    'homeLink' => CHtml::link('100 Юристов', '/'),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
]);
?>

<?php

function showCountry($regions)
{
    $regionCounter = 0;
    $regionsNumber = sizeof($regions);

    echo "<table class='table table-bordered'>";
    echo '<tr><th rowspan="2">Регион</th><th colspan="2" class="text-center">Цена покупки</th></tr>
<tr><th class="text-center">региона</th><th class="text-center">столицы</th></tr>';
    foreach ($regions as $region) {
        ++$regionCounter;
        echo '<tr><td>';
        echo CHtml::link($region['regionName'], Yii::app()->createUrl('admin/region/view', [
            'regionAlias' => $region['regionAlias'],
            'countryAlias' => $region['countryAlias'],
        ]));
        echo '</td><td><div>';
        echo CHtml::textField('buyPrice_region_' . $region['id'], MoneyFormat::rubles($region['buyPrice']), [
            'class' => 'form-control region-buy-price input-sm input-xs',
            'data-region-id' => $region['id'],
            'style' => 'max-width:50px',
        ]);
        echo '</td><td><div>';
        echo CHtml::textField('buyPrice_town_' . $region['capitalId'], MoneyFormat::rubles($region['capitalPrice']), [
            'class' => 'form-control region-capital-buy-price input-sm input-xs',
            'data-town-id' => $region['capitalId'],
            'style' => 'max-width:50px',
        ]);
        echo '</div></td></tr>';
    }
    echo '</table>';
}

?>
<style>
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 2px;
        padding-left: 15px;
    }
</style>

<div class="box">
    <div class="box-body">
        <h3>Россия</h3>
        <div class="row vert-margin30">
            <?php showCountry($regions['russia']); ?>
        </div>

        <h3>Беларусь</h3>
        <div class="row vert-margin30">
            <?php showCountry($regions['belarus']); ?>
        </div>

        <h3>Украина</h3>
        <div class="row vert-margin30">
            <?php showCountry($regions['ukraine']); ?>
        </div>


    </div>
</div>