<?php
/* @var $this LeadController */
/* @var $model Lead */

$this->setPageTitle("Редактирование лида " . $model->id . '. ' . Yii::app()->name);


$this->breadcrumbs = array(
    'Лиды' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Редактирование',
);
$this->widget('zii.widgets.CBreadcrumbs', array(
    'homeLink' => CHtml::link('100 Юристов', "/"),
    'separator' => ' / ',
    'links' => $this->breadcrumbs,
));

?>

<h1>Редактирование лида <?php echo $model->id; ?></h1>


<?php echo $this->renderPartial('_form', array(
    'model' => $model,
    'allDirections' => $allDirections,
)); ?>
