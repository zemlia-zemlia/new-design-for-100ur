<?php
/* @var $this CountryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle("Страны 100 юристов");

$this->breadcrumbs=array(
	'Страны',
);
?>
<?php
    $this->widget('zii.widgets.CBreadcrumbs', array(
        'homeLink'=>CHtml::link('Юристы и Адвокаты',"/"),
        'separator'=>' / ',
        'links'=>$this->breadcrumbs,
     ));
?>
<h1>Страны</h1>


