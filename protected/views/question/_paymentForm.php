<form action = "<?php echo Yii::app()->params['yandexPaymentAction'];?>" method="POST" class="vert-margin30">
    <input type="hidden" name="shopId" value="<?php echo Yii::app()->params['yandexShopId'];?>" />
    <input type="hidden" name="scid" value="<?php echo Yii::app()->params['yandexScid'];?>" />
    <input type="hidden" name="sum" value="<?php echo $question->price;?>" />
    <input type="hidden" name="customerNumber" value="<?php echo $question->id;?>" />
    <!--<input name="paymentType" value="ac" type="hidden">-->
    <input name="paymentType" value="" type="hidden">
    <input type="submit" value="Перейти к оплате <?php echo $question->price;?> рублей" class="btn btn-success btn-lg" />
    
</form>
<div class="flat-panel">
	<h5>
		<span class="glyphicon glyphicon-lock"></span> Платеж производится через безопасное соединение с использованием сервиса Яндекс Касса
	</h5>
</div>
<div class="center-align">
    <img src="/pics/payment/visa.png" alt="VISA" /> &nbsp;
    <img src="/pics/payment/mc.png" alt="MasterCard" /> &nbsp;
    <img src="/pics/payment/wm.png" alt="Web Money" /> &nbsp;
    <img src="/pics/payment/mir.png" alt="МИР" /> &nbsp;
    <img src="/pics/payment/yd.png" alt="Яндекс Деньги" />
</div>