<?php
/* @var $this MoneyController */
/* @var $model Money */

$this->breadcrumbs=array(
    'Moneys'=>array('index'),
    $model->id=>array('view','id'=>$model->id),
    'Update',
);

?>

<h1>Редактирование записи кассы <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>