<?php
    $this->pageTitle = 'Создание транзакции вебмастера' . Yii::app()->name;
?>

<div  class="vert-margin30">
<h1>Заявка на вывод средств</h1>
</div>


<?php echo $this->renderPartial('_form', [
    'model' => $transaction,
]); ?>