<?php
/* @var $this CampaignController */
/* @var $model Campaign */
$this->setPageTitle('Редактирование кампании #' . $model->id . '. ' . Yii::app()->name);

?>

<h1 class="vert-margin20">Редактирование кампании <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', [
    'model' => $model,
    'regions' => $regions,
]); ?>