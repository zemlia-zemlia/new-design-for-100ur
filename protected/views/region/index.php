<?php
/* @var $this RegionController */
/* @var $dataProvider CActiveDataProvider */
$pageTitle = "Юристы и Адвокаты России и СНГ. ";

$this->setPageTitle($pageTitle . Yii::app()->name);

Yii::app()->clientScript->registerMetaTag("Каталог Юристов и Адвокатов России и СНГ", "Description");

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
         
            $regionCounter = 0;
            $regionsNumber = sizeof($regions);
            
            foreach($regions as $region) {
            
                $regionCounter++;
                if($regionCounter == 1) {
                    echo '<div class="col-md-4">';
                } elseif($regionCounter == ceil($regionsNumber/3) || $regionCounter == ceil($regionsNumber/3)*2) {
                    echo '</div><div class="col-md-4">';
                }

            
                echo "<small>";
                echo CHtml::link($region['regionName'], Yii::app()->createUrl('region/view', array(
                        'regionAlias'   => $region['regionAlias'],
                        'countryAlias'  => $region['countryAlias'],
                    )));
                echo "</small><br />";
             
        }
        echo "</div> <!-- .col-md-4 -->";
    }

?>

<div class='panel gray-panel'>
    <div class="panel-body">
        
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

    </div>
</div>

