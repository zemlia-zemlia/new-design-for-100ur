<?php
    $this->setPageTitle("Отправка юристу заказа документа #" . $order->id . '. '. Yii::app()->name);
?>

<h1>Уточните параметры заказа</h1>

<p>
    <?php echo $order->docType->getClassName();?>.
    <?php echo $order->docType->name;?>
</p>
<p>
    <?php echo CHtml::encode($order->description);?>
</p>

<p>
    <strong>Выбран юрист:</strong>
    <br /> 
    <?php echo CHtml::link(CHtml::encode(trim($jurist->name . ' ' . $jurist->name2 . ' ' .$jurist->lastName)), Yii::app()->createUrl('user/view', ['id' => $jurist->id]));?>

</p>
<hr />

<?php echo $this->renderPartial('_formShort', [
    'order' =>  $order,
]);?>