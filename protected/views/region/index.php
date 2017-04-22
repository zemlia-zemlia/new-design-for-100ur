<?php
/* @var $this RegionController */
/* @var $dataProvider CActiveDataProvider */
$pageTitle = "Юристы и Адвокаты России и СНГ. ";

$this->setPageTitle($pageTitle . Yii::app()->name);

Yii::app()->clientScript->registerMetaTag("Каталог Юристов и Адвокатов России и СНГ", "Description");

$this->breadcrumbs=array(
	'Страны',
);

?>

<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('100 Юристов',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>

<div class="row">
    <div class="col-md-6"></div>
    <div class="col-md-6"><h2><?php echo CHtml::link('Россия', Yii::app()->createUrl('region/country', array('countryAlias' => 'russia')));?></h2></div>
</div>
        
<div class="row">
    <div class="col-md-6"></div>
    <div class="col-md-6"><h2><?php echo CHtml::link('Беларусь', Yii::app()->createUrl('region/country', array('countryAlias' => 'belarus')));?></h2></div>
</div>
<div class="row">
    <div class="col-md-6"></div>
    <div class="col-md-6"><h2><?php echo CHtml::link('Украина', Yii::app()->createUrl('region/country', array('countryAlias' => 'ukraine')));?></h2></div>
</div>

       


