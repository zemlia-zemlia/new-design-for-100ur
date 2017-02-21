<?php
/* @var $this MoneyController */
/* @var $model Money */

$this->setPageTitle("Новая запись в кассе. ". Yii::app()->name);


?>

<h1>Новая запись кассы</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>