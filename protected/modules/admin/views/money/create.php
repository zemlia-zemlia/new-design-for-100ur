<?php
/* @var $this MoneyController */

use App\models\Money;

/* @var $model Money */

$this->setPageTitle('Новая запись в кассе. ' . Yii::app()->name);

?>

<h1>Новая запись кассы</h1>

<?php $this->renderPartial('_form', ['model' => $model]); ?>