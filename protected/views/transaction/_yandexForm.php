<?php
//Yii::app()->clientScript->registerScriptFile('/js/balance.js');
?>

<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml" class="balance-form">    
    <input type="hidden" name="receiver" value="410012948838662">      
    <input type="hidden" name="label" value="u-<?php echo Yii::app()->user->id; ?>">
    <input type="hidden" name="quickpay-form" value="shop">    
    <input type="hidden" name="successURL" value="<?php echo Yii::app()->urlManager->baseUrl .  Yii::app()->request->requestUri; ?>">    
    <input type="hidden" name="targets" value="Пополнение баланса пользователя <?php echo Yii::app()->user->id; ?>">    
    <div class="form-group">
        <div class="input-group">
            <input type="text" name="sum" value="500" data-type="number" class="form-control text-right">
            <div class="input-group-addon">руб.</div>
        </div>
    </div>
    <div class="radio">
        <label><input type="radio" name="paymentType" value="PC" checked>Яндекс.Деньгами <br />
            <small>Комиссия 0.5%
            </small>
        </label>    
        <label><input type="radio" name="paymentType" value="AC">Банковской картой<br />
            <small>Комиссия 2%
            </small>
        </label> 
    </div>

    <input type="submit" class="btn btn-default" value="Пополнить баланс">
</form>