<?php
/** @var Answer $answer */

$target = (isset($target)) ? urlencode($target) : urlencode("Благодарность юристу");
$successUrl = (isset($successUrl)) ? urlencode($successUrl) : urlencode(Yii::app()->urlManager->baseUrl);
?>
<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml" class="form-inline donate-yurist-form">
    <input type="hidden" name="receiver" value="410012948838662">
    <input type="hidden" name="label" value="a-<?php echo $answer->id; ?>">
    <input type="hidden" name="quickpay-form" value="shop">
    <input type="hidden" name="successURL"
           value="<?php echo Yii::app()->createUrl('question/view', ['id' => $answer->questionId]); ?>">
    <input type="hidden" name="targets" value="<?php echo $target; ?>">
    <input type="hidden" name="paymentType" value="AC">
    <div class="form-group">
        <label for="sum">Сумма</label>
        <input type="text" name="sum" value="190" data-type="number" id="sum"
               class="form-control text-right">
    </div>
    <input type="submit" class="btn btn-default" value="Оплатить">
</form>

<!--
<iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/quickpay/shop-widget?account=410012948838662&quickpay=shop&payment-type-choice=on&mobile-payment-type-choice=on&writer=seller&targets=<?php echo $target; ?>&targets-hint=&default-sum=195&button-text=01&successURL=<?php echo $successUrl; ?>" width="450" height="198"></iframe>
-->