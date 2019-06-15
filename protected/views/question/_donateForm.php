<?php
/** @var Answer $answer */

$successUrl = (isset($successUrl)) ? urlencode($successUrl) : urlencode(Yii::app()->urlManager->baseUrl);
?>
<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml" class="donate-yurist-form">
    <div class="row">
        <input type="hidden" name="receiver" value="410012948838662">
        <input type="hidden" name="label" value="a-<?php echo $answer->id; ?>">
        <input type="hidden" name="quickpay-form" value="shop">
        <input type="hidden" name="successURL"
               value="<?php echo Yii::app()->createUrl('question/view', ['id' => $answer->questionId]); ?>">
        <input type="hidden" name="targets" value="<?php echo $target; ?>">
        <input type="hidden" name="paymentType" value="AC">

        <div class="col-sm-3 col-xs-3 text-right">
            <label for="sum">Сумма</label>
        </div>
        <div class="col-sm-2 col-xs-4">
            <input type="text" name="sum" value="190" data-type="number" id="sum"
                   class="form-control text-right">
        </div>
        <div class="col-sm-7 text-left">
            <input type="submit" class="btn btn-default" value="Оплатить">
            или
            <?php echo CHtml::link('Оставьте отзыв', Yii::app()->createUrl('user/testimonial', ['id' => $answer->authorId]), ['class' => 'btn btn-default']); ?></a>
        </div>
    </div>
</form>