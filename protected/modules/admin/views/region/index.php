<?php
/* @var $this RegionController */
/* @var $dataProvider CActiveDataProvider */
$pageTitle = "Юристы и Адвокаты России и СНГ. ";

$this->setPageTitle($pageTitle . Yii::app()->name);

Yii::app()->clientScript->registerMetaTag("Каталог Юристов и Адвокатов России и СНГ", "Description");
Yii::app()->clientScript->registerScriptFile('/js/admin/region.js');

$this->breadcrumbs=array(
	'Регионы',
);

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Юристы и Адвокаты',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
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
        foreach($regions as $region) {

            $regionCounter++;
            echo "<tr><td>";
            echo CHtml::link($region['regionName'], Yii::app()->createUrl('admin/region/view', array(
                    'regionAlias'   => $region['regionAlias'],
                    'countryAlias'  => $region['countryAlias'],
                )));
            echo "</td><td><div>";
            echo CHtml::textField('buyPrice', $region['buyPrice'], array('class' => 'form-control region-buy-price', 'data-id'=>$region['id']));
            echo "</div></td></tr>";
             
        }
        echo "</table>";
    }

?>


        
        <h2>Россия</h2>
        <div class="row vert-margin30">
            <?php showCountry($regions['russia']);?>
        </div>
        
        <h2>Беларусь</h2>
        <div class="row vert-margin30">
            <?php showCountry($regions['belarus']);?>
        </div>
        
        <h2>Украина</h2>
        <div class="row vert-margin30">
            <?php showCountry($regions['ukraine']);?>
        </div>


