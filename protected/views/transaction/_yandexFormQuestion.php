<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml" class="balance-form">
    <input type="hidden" name="receiver" value="410012948838662">
    <input type="hidden" name="label" value="q-<?php echo $questionId; ?>">
    <input type="hidden" name="quickpay-form" value="shop">
    <input type="hidden" name="successURL"
           value="<?php echo Yii::app()->createUrl('question/view', ['id' => $questionId]); ?>">
    <input type="hidden" name="targets" value="Оплата вопроса <?php echo $questionId; ?>">
    <input type="hidden" name="paymentType" value="AC">
    <input type="hidden" name="sum" value="<?php echo $questionPrice; ?>" data-type="number"
           class="form-control text-right">
    <input type="submit" class="btn btn-default" value="Оплатить">
</form>