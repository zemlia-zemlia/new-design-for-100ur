<?php
$this->pageTitle=Yii::app()->name . ' - Аккаунт не активирован';
?>
<h1>Ой, не получается активировать аккаунт</h1>
<p>
<?php
    if(isset($message))
    echo $message;    
?>
</p>