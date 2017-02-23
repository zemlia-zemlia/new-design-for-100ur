<?php
/* @var $this CampaignController */
/* @var $model Campaign */

$this->breadcrumbs=array(
	'Кампании'=>array('index'),
	'Новая',
);

?>

<h1>Новая кампания</h1>

<?php $this->renderPartial('application.views.campaign._form', array(
    'model'         =>  $model,
    'buyersArray'   =>  $buyersArray,
    'regions'       =>  $regions,
    )); ?>