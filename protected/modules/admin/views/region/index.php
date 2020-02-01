<?php
/* @var $this RegionController */
/* @var $dataProvider CActiveDataProvider */
$pageTitle = "Юристы и Адвокаты России и СНГ. ";

$this->setPageTitle($pageTitle . Yii::app()->name);

Yii::app()->clientScript->registerMetaTag("Каталог Юристов и Адвокатов России и СНГ", "Description");
Yii::app()->clientScript->registerScriptFile('/js/admin/region.js');

$this->breadcrumbs = array(
    'Цены на выкуп лидов по регионам',
);

?>

<?php
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('100 Юристов', "/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));
?>

<?php

function showCountry($regions)
{

    //CustomFuncs::printr($regions);
    $regionCounter = 0;
    $regionsNumber = sizeof($regions);

    echo "<table class='table table-bordered'>";
    echo '<tr><th>Регион</th><th>Цена покупки</th></tr>';
    foreach ($regions as $region) {

        $regionCounter++;
        echo "<tr><td>";
        echo CHtml::link($region['regionName'], Yii::app()->createUrl('admin/region/view', array(
            'regionAlias' => $region['regionAlias'],
            'countryAlias' => $region['countryAlias'],
        )));
        echo "</td><td><div>";
        echo CHtml::textField('buyPrice', MoneyFormat::rubles($region['buyPrice']), array(
            'class' => 'form-control region-buy-price input-sm input-xs',
            'data-id' => $region['id'],
            'style' => 'max-width:50px',
        ));
        echo "</div></td></tr>";

    }
    echo "</table>";
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