<?php
    $target = (isset($target))?urlencode($target):urlencode("Благодарность юристу");
    $successUrl = (isset($successUrl))?urlencode($successUrl):urlencode(Yii::app()->urlManager->baseUrl);
?>

<iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/quickpay/shop-widget?account=410012948838662&quickpay=shop&payment-type-choice=on&mobile-payment-type-choice=on&writer=seller&targets=<?php echo $target;?>&targets-hint=&default-sum=195&button-text=01&successURL=<?php echo $successUrl;?>" width="450" height="198"></iframe>