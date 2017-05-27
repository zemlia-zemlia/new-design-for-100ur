<?php
/* @var $this CampaignController */
/* @var $model Campaign */
$this->setPageTitle('Новая кампания');

$this->breadcrumbs=array(
	'Кабинет покупателя'=>array('/cabinet'),
	'Новая кампания',
);

?>
<div class="vert-margin20">
<h1>Новая кампания</h1>
</div>
<?php 
    $this->renderPartial('_form', array(
        'model' =>  $model,
        'regions'   =>  $regions,
    )); 
?>