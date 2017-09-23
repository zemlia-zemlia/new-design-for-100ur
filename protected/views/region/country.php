<?php
$this->setPageTitle(CHtml::encode($country->name) . '. '. Yii::app()->name);

Yii::app()->clientScript->registerMetaTag("Каталог Юристов и Адвокатов " . CHtml::encode($country->name), "Description");

$this->breadcrumbs=array(
        'Страны'   =>  array('/region'),
	CHtml::encode($country->name),
);

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('100 Юристов',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<h1><?php echo CHtml::encode($country->name);?>: регионы</h1>
<?php
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
?>