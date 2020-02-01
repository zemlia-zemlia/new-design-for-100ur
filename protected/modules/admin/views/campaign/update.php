<?php
/* @var $this CampaignController */
/* @var $model Campaign */

$this->breadcrumbs=array(
    'Кампании'=>array('index'),
    $model->id=>array('view','id'=>$model->id),
    'Редактирование',
);

$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink'=>CHtml::link('100 юристов', "/admin"),
    'separator'=>' / ',
    'links'=>$this->breadcrumbs,
 ));


?>

<h1>Редактирование кампании <?php echo $model->id; ?></h1>

<?php $this->renderPartial('application.views.campaign._form', array(
    'model'         =>  $model,
    'buyersArray'   =>  $buyersArray,
    'regions'       =>  $regions,
    )); ?>