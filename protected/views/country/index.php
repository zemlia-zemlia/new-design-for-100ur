<?php
/* @var $this CountryController */
/* @var $dataProvider CActiveDataProvider */

$this->setPageTitle('Страны 100 юристов');

$this->breadcrumbs = [
    'Страны',
];
?>
<?php
    $this->widget('zii.widgets.CBreadcrumbs', [
        'homeLink' => CHtml::link('Юристы и Адвокаты', '/'),
        'separator' => ' / ',
        'links' => $this->breadcrumbs,
     ]);
?>
<h1>Страны</h1>


