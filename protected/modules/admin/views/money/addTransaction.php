<?php
/* @var $this MoneyController */

use App\models\Money;

/* @var $model Money */

$this->setPageTitle('Новая транзакция между счетами. ' . Yii::app()->name);

?>

<h1>Новая транзакция между счетами</h1>

<?php $this->renderPartial('_formTransaction', [
        'model' => $model,
        'moneyRecord1' => $moneyRecord1,
        'moneyRecord2' => $moneyRecord2,
    ]);
?>